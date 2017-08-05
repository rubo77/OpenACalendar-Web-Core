<?php

namespace sysadmin\forms;


use oBaseSeriesReport;
use Silex\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class RunSeriesByTimeReportForm extends AbstractType{

	/** @var  BaseSeriesReport */
	protected $report;

	protected $timeZoneName = "Europe/London";

    /** @var Application */
    protected $app;

    function __construct(Application $app, $report)
	{
        $this->app = $app;
		$this->report = $report;

		$this->timeperiodChoices = array(
			 '1 hour' =>"PT1H",
			 '4 hours' =>"PT4H",
			 '12 hours' =>"PT12H",
			 '1 day' =>"P1D",
			 '1 week' =>"P7D",
			 '1 month' =>"P1M" ,
			 '3 months' =>"P3M",
			 '6 months' =>"P6M",
			 '1 year' =>"P1Y",
		);
	}

	protected $timeperiodChoices;

	public function buildForm(FormBuilderInterface $builder, array $options) {

		$builder
			->add('output', 'choice', array(
				'expanded' => true,
                'choices' => array('Table in Web Browser' => 'htmlTable', 'Download CSV' => 'csv'),
                'data' => 'htmlTable',
                'choices_as_values'=>true
			));

		if ($this->report->getHasFilterSite()) {

			$builder->add('site_id', IntegerType::class ,array(
				'label'=>'Site ID',
				'required'=>false,
				'data'=> ($this->app['config']->isSingleSiteMode ? $this->app['config']->singleSiteID : null),
			));
		}

		$builder->add('start_at', DateTimeType::class ,array(
			'label'=>'Start Date & Time',
			'model_timezone' => 'UTC',
			'view_timezone' => $this->timeZoneName,
			'required'=>true,
			'data'=>new \DateTime("2013-01-01 00:00:00", new \DateTimeZone('UTC')),
		));

		$builder->add('end_at', DateTimeType::class ,array(
			'label'=>'End Date & Time',
			'model_timezone' => 'UTC',
			'view_timezone' => $this->timeZoneName,
			'required'=>false
		));

		$builder
			->add('timeperiod', ChoiceType::class, array(
				'expanded' => true,
				'choices' => $this->timeperiodChoices,
				'data' => "P1M",
                'choices_as_values'=>true
			));
	}
	
	public function getName() {
		return 'RunReportForm';
	}
	
	public function getDefaultOptions(array $options) {
		return array(
		);
	}
	
}
