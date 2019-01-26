#!/bin/bash
ssh -t -L8000:localhost:8000 invoicelion@ams01.usecue.nl 'cd app; bash start.sh'
