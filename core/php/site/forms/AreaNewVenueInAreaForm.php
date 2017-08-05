<?php

namespace site\forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use models\SiteModel;
use repositories\builders\CountryRepositoryBuilder;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class AreaNewVenueInAreaForm extends AbstractType{

	/** @var SiteModel **/
	protected $site;
	
	function __construct(SiteModel $site) {
		$this->site = $site;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		
		$builder->add('title', TextType::class, array(
			'label'=>'Title',
			'required'=>true, 
			'max_length'=>VARCHAR_COLUMN_LENGTH_USED, 
			'attr' => array('autofocus' => 'autofocus')
		));
		
		$builder->add('description', TextareaType::class, array(
			'label'=>'Description',
			'required'=>false
		));
		
		$builder->add('lat', HiddenType::class, array());
		$builder->add('lng', HiddenType::class, array());

	}
	
	public function getName() {
		return 'AreaNewVenueInAreaForm';
	}
	
	public function getDefaultOptions(array $options) {
		return array(
		);
	}
	
}