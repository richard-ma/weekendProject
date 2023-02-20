#!/bin/bash

LOGFILE=$1

awk '{print $7}' $LOGFILE | sort | uniq -c | sort -nr
