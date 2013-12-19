#!/bin/sh
# Remove the application XML and compiled templates cached files to force
# the refresh of the application
# TODO : move to a php admin script
rm -rf xml_cache/*
rm -rf templates_c/*
