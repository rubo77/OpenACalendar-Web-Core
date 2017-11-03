<?php



namespace sysadmin\forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class LogInUserForm  extends AbstractType {
	
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		
		$builder->add('password', PasswordType::class, array('label'=>'Your Password','required'=>true));
		$builder->add('extrapassword', PasswordType::class, array('label'=>'Extra Password','required'=>true));
		
	}
	
	public function getName() {
		return 'LogInUserForm';
	}
	
}

