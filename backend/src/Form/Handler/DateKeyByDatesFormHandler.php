<?php

namespace App\Form\Handler;

use App\Doctrine\DateKeyManager;
use App\Form\Model\DateKeyDatesModel;
use DateInterval;
use DatePeriod;

class DateKeyByDatesFormHandler extends AbstractApiFormHandler
{
    /**
     * @var DateKeyManager
     */
    private $manager;

    public function __construct(DateKeyManager $manager)
    {
        $this->manager = $manager;
    }

    protected function success()
    {
        /** @var DateKeyDatesModel $data */
        $data = $this->form->getData();

        $period = new DatePeriod(
            $data->getFrom(),
            new DateInterval('P1D'),
            $data->getTo()->modify('+1 day')
        );

        $this->manager->insertCollectionForPeriod($period, $data->getKeyTemplate());

        return true;
    }
}
