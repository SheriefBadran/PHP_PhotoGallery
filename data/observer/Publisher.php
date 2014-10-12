<?php
	class Publisher extends PublisherBase {

		private $subscribers = array();
		public function attach (iSubscriber $subscriber) {

			//could also use array_push($this->subscribers, $subscriber);
			$this->subscribers[] = $subscriber;
		}

		public function detach (iSubscriber $subscriber) {

			//$key = array_search($subscriber, $this->subscribers);
			foreach ($this->subscribers as $okey => $oval) {
				if ($oval == $subscriber) { 
					unset($this->subscribers[$okey]);
				}
			}
		}

		public function notify () {

			foreach ($this->subscribers as $subscriber) {

				$subscriber->subscribe($this);
			}
		}
	}