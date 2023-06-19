#!/bin/bash

LOGFILE=$1

awk '{print $4}' $LOGFILE | cut -c 14-21 | sort | uniq -c | sort -nr
