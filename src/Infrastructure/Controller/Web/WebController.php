<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Web;

use App\Application\Task\Command\ChangeTaskStatusCommand;
use App\Application\Task\Command\CreateTaskCommand;
use App\Application\User\Command\SyncUsersFromApiCommand;
use App\Domain\Task\Repository\TaskRepositoryInterface;
use App\Domain\Task\ValueObject\TaskId;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Repository\TaskEventStoreRepository;
use App\Infrastructure\Security\SecurityUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;

class WebController extends AbstractController
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private TaskRepositoryInterface $taskRepository,
        private TaskEventStoreRepository $eventStoreRepository,
        private MessageBusInterface $messageBus,
    ) {
    }

    #[Route('/web/login', name: 'web_login', methods: ['GET', 'POST'])]
    public function login(Request $request): Response
    {
        if ($request->isMethod(Request::METHOD_POST)) {
            $email = $request->request->get('email');
            $user = $this->userRepository->findByEmail($email);

            if (!$user) {
                return $this->render('login.html.twig', ['error' => 'User not found.']);
            }

            $token = bin2hex(random_bytes(32));
            $user->setApiToken($token);
            $this->userRepository->save($user);

            $request->getSession()->set('api_token', $token);

            return new RedirectResponse('/web/dashboard');
        }

        return $this->render('login.html.twig', ['error' => null]);
    }

    #[Route('/web/logout', name: 'web_logout', methods: ['GET'])]
    public function logout(Request $request): Response
    {
        $request->getSession()->remove('api_token');
        return new RedirectResponse('/web/login');
    }

    #[Route('/web/dashboard', name: 'web_dashboard', methods: ['GET'])]
    public function dashboard(): Response
    {
        $tasks = $this->taskRepository->findAll();
        $users = $this->userRepository->findAll();

        /** @var SecurityUser $securityUser */
        $securityUser = $this->getUser();
        $currentUser = $securityUser->getDomainUser();

        return $this->render('dashboard.html.twig', [
            'tasks' => $tasks,
            'users' => $users,
            'currentUser' => $currentUser,
        ]);
    }

    #[Route('/web/task/create', name: 'web_task_create', methods: ['POST'])]
    public function createTask(Request $request): Response
    {
        $title = $request->request->get('title');
        $description = $request->request->get('description');
        $assignedUserId = $request->request->get('assignedUserId');

        $this->messageBus->dispatch(
            new CreateTaskCommand($title, $description, $assignedUserId),
        );

        return new RedirectResponse('/web/dashboard');
    }

    #[Route('/web/task/{id}/status', name: 'web_task_status', methods: ['POST'])]
    public function changeStatus(string $id, Request $request): Response
    {
        $newStatus = $request->request->get('status');

        $this->messageBus->dispatch(
            new ChangeTaskStatusCommand($id, $newStatus),
        );

        return new RedirectResponse('/web/dashboard');
    }

    #[Route('/web/task/{id}/history', name: 'web_task_history', methods: ['GET'])]
    public function taskHistory(string $id): Response
    {
        $task = $this->taskRepository->findById(new TaskId($id));
        $events = $this->eventStoreRepository->findByTaskId($id);

        return $this->render('task_history.html.twig', [
            'task' => $task,
            'events' => $events,
        ]);
    }

    #[Route('/web/sync-users', name: 'web_sync_users', methods: ['GET'])]
    public function syncUsers(Request $request): Response
    {
        $envelope = $this->messageBus->dispatch(new SyncUsersFromApiCommand());
        $synced = $envelope->last(HandledStamp::class)->getResult();

        if ($request->getSession()->has('api_token')) {
            $this->addFlash('success', sprintf('Synced %d users from JSONPlaceholder.', $synced));

            return new RedirectResponse('/web/dashboard');
        }
        return new RedirectResponse('/web/login');
    }
}
