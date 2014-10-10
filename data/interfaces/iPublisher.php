<?php
	interface iPublisher {

		function attach (Subscriber $subscriber);

		function detach (Subscriber $subscriber);

		function notify ();

	}