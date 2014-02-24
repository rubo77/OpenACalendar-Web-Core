<?php


use models\UserAccountModel;
use models\SiteModel;
use models\GroupModel;
use models\EventModel;
use \SearchForDuplicateEvents;

/**
 *
 * @package Core
 * @link http://ican.openacalendar.org/ OpenACalendar Open Source Software
 * @license http://ican.openacalendar.org/license.html 3-clause BSD
 * @copyright (c) 2013-2014, JMB Technology Limited, http://jmbtechnology.co.uk/
 * @author James Baster <james@jarofgreen.co.uk>
 */
class SearchForDuplicateEventsTest  extends \PHPUnit_Framework_TestCase {

	/** with venues **/
	function testScoreNoMatch1() {
		$site = new SiteModel();
		
		$eventNew = new models\EventModel();
		$eventNew->setStartAt(new \DateTime("12th Feb 2012 10:00:00"));
		$eventNew->setEndAt(new \DateTime("12th Feb 2012 13:00:00"));
		$eventNew->setUrl("http://www.greatevent.com");
		$eventNew->setVenueId(34);
		
		$eventExisting = new models\EventModel();
		$eventExisting->setStartAt(new \DateTime("12th March 2012 10:00:00"));
		$eventExisting->setEndAt(new \DateTime("12th March 2012 13:00:00"));
		$eventExisting->setUrl("http://www.okevent.com");
		$eventNew->setVenueId(78);
		
		$sfde = new SearchForDuplicateEvents($eventNew, $site);
		
		$score = $sfde->getScoreForConsideredEvent($eventExisting);
		
		$this->assertEquals(0, $score);
	}

	/** with areas **/
	function testScoreNoMatch2() {
		$site = new SiteModel();
		
		$eventNew = new models\EventModel();
		$eventNew->setStartAt(new \DateTime("12th Feb 2012 10:00:00"));
		$eventNew->setEndAt(new \DateTime("12th Feb 2012 13:00:00"));
		$eventNew->setUrl("http://www.greatevent.com");
		$eventNew->setAreaId(34);
		
		$eventExisting = new models\EventModel();
		$eventExisting->setStartAt(new \DateTime("12th March 2012 10:00:00"));
		$eventExisting->setEndAt(new \DateTime("12th March 2012 13:00:00"));
		$eventExisting->setUrl("http://www.okevent.com");
		$eventNew->setAreaId(78);
		
		$sfde = new SearchForDuplicateEvents($eventNew, $site);
		
		$score = $sfde->getScoreForConsideredEvent($eventExisting);
		
		$this->assertEquals(0, $score);
	}

	/** little info as possible **/
	function testScoreNoMatch3() {
		$site = new SiteModel();
		
		$eventNew = new models\EventModel();
		$eventNew->setStartAt(new \DateTime("12th Feb 2012 10:00:00"));
		$eventNew->setEndAt(new \DateTime("12th Feb 2012 13:00:00"));
		
		$eventExisting = new models\EventModel();
		$eventExisting->setStartAt(new \DateTime("12th March 2012 10:00:00"));
		$eventExisting->setEndAt(new \DateTime("12th March 2012 13:00:00"));
		
		$sfde = new SearchForDuplicateEvents($eventNew, $site);
		
		$score = $sfde->getScoreForConsideredEvent($eventExisting);
		
		$this->assertEquals(0, $score);
	}

	function testScoreURLSame() {
		$site = new SiteModel();
		
		$eventNew = new models\EventModel();
		$eventNew->setStartAt(new \DateTime("12th Feb 2012 10:00:00"));
		$eventNew->setEndAt(new \DateTime("12th Feb 2012 13:00:00"));
		$eventNew->setUrl("http://www.greatevent.com");
		
		$eventExisting = new models\EventModel();
		$eventExisting->setStartAt(new \DateTime("12th March 2012 10:00:00"));
		$eventExisting->setEndAt(new \DateTime("12th March 2012 13:00:00"));
		$eventExisting->setUrl("http://www.greatevent.com");
		
		$sfde = new SearchForDuplicateEvents($eventNew, $site);
		
		$score = $sfde->getScoreForConsideredEvent($eventExisting);
		
		$this->assertEquals(1, $score);
	}
	

	function testScoreStartSame() {
		$site = new SiteModel();
		
		$eventNew = new models\EventModel();
		$eventNew->setStartAt(new \DateTime("12th Feb 2012 10:00:00"));
		$eventNew->setEndAt(new \DateTime("12th Feb 2012 13:00:00"));
		
		$eventExisting = new models\EventModel();
		$eventExisting->setStartAt(new \DateTime("12th Feb 2012 10:00:00"));
		$eventExisting->setEndAt(new \DateTime("12th March 2012 13:00:00"));
		
		$sfde = new SearchForDuplicateEvents($eventNew, $site);
		
		$score = $sfde->getScoreForConsideredEvent($eventExisting);
		
		$this->assertEquals(1, $score);
	}

	function testScoreEndSame() {
		$site = new SiteModel();
		
		$eventNew = new models\EventModel();
		$eventNew->setStartAt(new \DateTime("12th Feb 2012 10:00:00"));
		$eventNew->setEndAt(new \DateTime("12th March 2012 13:00:00"));
		
		$eventExisting = new models\EventModel();
		$eventExisting->setStartAt(new \DateTime("12th March 2012 10:00:00"));
		$eventExisting->setEndAt(new \DateTime("12th March 2012 13:00:00"));
		
		$sfde = new SearchForDuplicateEvents($eventNew, $site);
		
		$score = $sfde->getScoreForConsideredEvent($eventExisting);
			
		$this->assertEquals(1, $score);
	}
	

	function testScoreStartEndSame() {
		$site = new SiteModel();
		
		$eventNew = new models\EventModel();
		$eventNew->setStartAt(new \DateTime("12th March 2012 10:00:00"));
		$eventNew->setEndAt(new \DateTime("12th March 2012 13:00:00"));
		
		$eventExisting = new models\EventModel();
		$eventExisting->setStartAt(new \DateTime("12th March 2012 10:00:00"));
		$eventExisting->setEndAt(new \DateTime("12th March 2012 13:00:00"));
		
		$sfde = new SearchForDuplicateEvents($eventNew, $site);
		
		$score = $sfde->getScoreForConsideredEvent($eventExisting);
		
		$this->assertEquals(2, $score);
	}
	

	function dataForTestScoreSummaryCompare() {
		return array(
				array('wibble','wobble',0),
				array('wibble wibble','wibble wibble', 1),
				array('wibble','wiBBle', 1),
				array('wibble cat dog','wiBBle', 1),
				array('wibble','cat wiBBle dof', 1),
				array('alpha beta','alphabeta', 0),
			);
	}
	
	/**
     * @dataProvider dataForTestScoreSummaryCompare
     */
	function testScoreSummaryCompare($summary1, $summary2, $scoreExpected) {
		$site = new SiteModel();
		
		$eventNew = new models\EventModel();
		$eventNew->setStartAt(new \DateTime("12th Feb 2012 10:00:00"));
		$eventNew->setEndAt(new \DateTime("12th Feb 2012 13:00:00"));
		$eventNew->setSummary($summary1);
		
		$eventExisting = new models\EventModel();
		$eventExisting->setStartAt(new \DateTime("12th March 2012 10:00:00"));
		$eventExisting->setEndAt(new \DateTime("12th March 2012 13:00:00"));
		$eventExisting->setSummary($summary2);
		
		$sfde = new SearchForDuplicateEvents($eventNew, $site);
		
		$score = $sfde->getScoreForConsideredEvent($eventExisting);
		
		$this->assertEquals($scoreExpected, $score);
	}	

	function testScoreVenueSame() {
		$site = new SiteModel();
		
		$eventNew = new models\EventModel();
		$eventNew->setStartAt(new \DateTime("12th April 2012 10:00:00"));
		$eventNew->setEndAt(new \DateTime("12th April 2012 13:00:00"));
		$eventNew->setVenueId(34);
		
		$eventExisting = new models\EventModel();
		$eventExisting->setStartAt(new \DateTime("12th March 2012 10:00:00"));
		$eventExisting->setEndAt(new \DateTime("12th March 2012 13:00:00"));
		$eventExisting->setVenueId(34);
		
		$sfde = new SearchForDuplicateEvents($eventNew, $site);
		
		$score = $sfde->getScoreForConsideredEvent($eventExisting);
		
		$this->assertEquals(1, $score);
	}

	function testScoreAreaSame() {
		$site = new SiteModel();
		
		$eventNew = new models\EventModel();
		$eventNew->setStartAt(new \DateTime("12th April 2012 10:00:00"));
		$eventNew->setEndAt(new \DateTime("12th April 2012 13:00:00"));
		$eventNew->setAreaId(34);
		
		$eventExisting = new models\EventModel();
		$eventExisting->setStartAt(new \DateTime("12th March 2012 10:00:00"));
		$eventExisting->setEndAt(new \DateTime("12th March 2012 13:00:00"));
		$eventExisting->setAreaId(34);
		
		$sfde = new SearchForDuplicateEvents($eventNew, $site);
		
		$score = $sfde->getScoreForConsideredEvent($eventExisting);
		
		$this->assertEquals(1, $score);
	}

	
}


