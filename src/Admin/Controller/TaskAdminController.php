<?php

namespace App\Admin\Controller;

use App\Core\Application\UseCase\Admin\ApproveTaskUseCase;
use App\Core\Application\UseCase\Admin\RejectTaskUseCase;
use App\Core\Infrastructure\Entity\Task;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskAdminController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Task::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('status');
    }

    public function configureActions(Actions $actions): Actions
    {
        $approveAction = Action::new('approveTask')
            ->linkToRoute(
                'admin.task.approve',
                function (Task $task) {
                    return [
                        'taskId' => $task->getId(),
                    ];
                }
            )
            ->displayIf(fn(Task $task) => $task->getStatus() === Task::STATUS_REVIEW);

        $rejectAction = Action::new('rejectTask')
            ->linkToRoute(
                'admin.task.reject',
                function (Task $task) {
                    return [
                        'taskId' => $task->getId(),
                    ];
                }
            )
            ->displayIf(fn(Task $task) => $task->getStatus() === Task::STATUS_REVIEW);


        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)

            ->add(Crud::PAGE_DETAIL, $approveAction)
            ->add(Crud::PAGE_DETAIL, $rejectAction)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
        ;
    }

    #[Route('/approve', name: 'admin.task.approve')]
    public function approve(
        Request            $request,
        ApproveTaskUseCase $useCase,
        AdminUrlGenerator $generator
    ) {
        $task = $request->get('taskId');
        $useCase->approve($task);
        $index = $generator
            ->setController($this::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($task)
            ->generateUrl();
        return $this->redirect($index);
    }

    #[Route('/reject', name: 'admin.task.reject')]
    public function reject(
        Request            $request,
        RejectTaskUseCase $useCase,
        AdminUrlGenerator $generator
    ) {
        $task = $request->get('taskId');
        $useCase->reject($task);
        $index = $generator
            ->setController($this::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($task)
            ->generateUrl();
        return $this->redirect($index);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined();
    }
}
