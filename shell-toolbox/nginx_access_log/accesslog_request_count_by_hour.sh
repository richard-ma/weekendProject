#!/bin/bash

LOGFILE=$1

awk '{print $4}' $LOGFILE | cut -c 14-15 | sort | uniq -c | sort -nr
