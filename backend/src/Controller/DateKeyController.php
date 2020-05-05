<?php

namespace App\Controller;

use App\Doctrine\DateKeyManager;
use App\Entity\DateKey;
use App\Form\Handler\DateKeyByDatesFormHandler;
use App\Form\Model\DateKeyDatesModel;
use App\Form\Type\DateKeyByDatesType;
use App\Response\ApiProblem;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Create date keys by provided dates.
 *
 * @Route("/date-keys")
 */
class DateKeyController extends ApiBaseController
{
    /**
     * Create new DataKeys for period of time.
     *
     * @Route(path="/period", methods={"POST"})
     *
     * @SWG\Parameter(
     *      name="form",
     *      in="body",
     *      @Model(type=App\Form\Type\DateKeyByDatesType::class)
     * ),
     * @SWG\Response(
     *     response=204,
     *     description="Created successfully",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Form error",
     * )
     * @SWG\Tag(name="DataKeys")
     */
    public function create(DateKeyByDatesFormHandler $formHandler): Response
    {
        $formHandler->buildForm(DateKeyByDatesType::class, new DateKeyDatesModel());
        if (false === $formHandler->process()) {
            $this->errorResponse(ApiProblem::BAD_REQUEST, $formHandler->getErrors());
        }

        return $this->emptyView();
    }

    /**
     * Get DataKeys resources list.
     *
     * @Route(methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="DataKeys returned successfully",
     *     @SWG\Schema(
     *         title="data",
     *         type="array",
     *         @SWG\Items(ref=@Model(type=DateKey::class, groups={"List"}))
     *     )
     * ),
     * @SWG\Response(
     *     response=204,
     *     description="No content",
     * ),
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     type="integer",
     *     description="Page number",
     *     default=1
     * ),
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     description="Cases limit per page",
     *     default=20
     * ),
     * @SWG\Tag(name="DataKeys")
     *
     * @return Response|void
     */
    public function list(DateKeyManager $manager): Response
    {
        return $this->paginatedResponse($manager->getAll(), ['List']);
    }
}
