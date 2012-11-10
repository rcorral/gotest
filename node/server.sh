#!/bin/bash
action=$1

if [[ $action == 'start' ]]; then
	node_modules/forever/bin/forever -s -l logs/forever.log -o logs/out.log -e logs/err.log app.js &
	echo 'started'
elif [[ $action == 'stop' ]]; then
	node_modules/forever/bin/forever stop app.js
	echo 'stopped'
elif [[ $action == 'list' ]]; then
	node_modules/forever/bin/forever list
else
	echo 'Please supply a command.'
	echo 'start - starts app.js'
	echo 'stop  - stops app.js'
	echo 'list  - lists running daemons'
fi

exit;