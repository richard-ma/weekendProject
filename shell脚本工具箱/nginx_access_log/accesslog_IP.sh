#!/bin/bash

LOGFILE=$1

awk '{print $1}' $LOGFILE | sort | uniq -c | sort -nr
