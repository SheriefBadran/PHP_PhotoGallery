<?php
	abstract class PublisherBase {

		abstract function attach(iSubscriber $subscriber);
	    abstract function detach(iSubscriber $subscriber);
	    abstract function notify();
	}