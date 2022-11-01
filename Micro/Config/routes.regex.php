<?php
###################################
//# Resource Parameter Config
//#################################
const RG_BRACKETS = '{id}';
const NEEDLE_BRACKETS = '}';

const RG_TWO_POINTS = ':id';
const NEEDLE_TWO_POINTS = ':';

###################################
//# Routes Capture Group Regex
//#################################
const TWO_POINTS = '#:([\w]+)#';
const BRACKETS = '#{(.*?)}#';


//##################################
//# Routes Validation Regex
//#################################
const MIXED = '([A-Za-z0-9]+)';
const MIXED_DASH = '([A-Za-z0-9-]+)';
const MIXED_REQ_DASH = '([A-Za-z0-9]*-[A-Za-z0-9]*)';
const LETTERS = '([a-zA-Z]+)';
const LETTERS_LOWER = '([a-z]+)';
const LETTERS_UPPER = '([A-Z]+)';
const LETTERS_UPPER_DASH = '([A-Z-]+)';
const LETTERS_DASH = '([a-zA-Z-]+)';
const LETTERS_LOWER_REQ_DASH = '([a-z]*-[a-z]*)';
const LETTERS_UPPER_REQ_DASH = '([A-Z]*-[A-Z]*)';
const LETTERS_REQ_DASH = '([a-zA-Z]*-[a-zA-Z]*)';
const INTEGER = '([0-9]+)';
const INTEGER_DASH = '([0-9-]+)';
const INTEGER_REQ_DASH = '([0-9]*-[0-9]*)';

##################################
//# ACTIVE CAPTURE & RESOURCES
//################################

//define('ROUTE_PARAM' , RG_BRACKETS);
//define('CAPTURE', BRACKETS);
//define('NEEDLE', BRACKETS);

const ROUTE_PARAM = RG_TWO_POINTS;
const CAPTURE = TWO_POINTS;
const NEEDLE = NEEDLE_TWO_POINTS;
