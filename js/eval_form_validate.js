/********************followings are used in eval_type_method.php file********************/

rules = {
   eval_type: {   
    required:[true,'Evaluation Type']
   },
   eval_method: {   
    required:[true,'Evaluation Method']
   },
   display_id:  {   
    required:[true,'Evaluation ID used by MIL']
   },
   eval_funding_sources: {   
    required:[true,'Evaluation Funding Sources']
   },
   /*eval_yr:{
    required:[true,'Evaluation Year'],
    number: [true,'Evaluation Year'],
    min: 2000,
    max: 2012
   },*/
   eval_month:{
    required:[true,'Evaluation Month'],
    number: [true,'Evaluation Month'],
    min: 1,
    max: 12
   }, 
   irr_sys_type: {   
    required:[true,'Irrigation System Type']
   },
   irr_sys_du:{   
    required: [true,'Irrigation System DU or EU'],
    number : [true,'Irrigation System DU or EU'],
    min:0
   },
   acre:{
    required:[true,'Acre'],
    number:[true,'Acre'],
    min:0
   },
   crop_category : {   
    required:[true,'Land Use']
   },
   nir_water_use:{
    required:"#nir_checkbox:checked",
    number:[true,'NIR'],
    min: 0,
    max: 99
   },
   actual_water_use:{
    required:"#awu_checkbox:checked",
    number:[true,'NIR'],
    min: 0,
    max:100
   },
   county_id: {   
    required:[true,'County']
   },
   zip_code:{
     required:[true, 'Zip Code'],
     digits:[true,'Soil Type'],
     minlength: 5,
     maxlength: 5
  },
  soil_type:{
    required:[true, 'Soil Type'],
    number:[true,'Soil Type'],
    min: 0
  },
  water_source:{
     required:[true,"Water Source"]
  },
  tds:{
   number:[true,"TDS"],
   min: 0
   },
   ph:{
   number:[true,"PH"],
    min: 0,
    max: 14
   },
  pump_type:{
      required:[true,"Pump Type"]
  },
  has_flow_meter:{
  required:[true,"Has Permanent Flow Meter"]
  },
  device_gpm:{
  required:[true,"Device Used to Measure GPM"]
  },
  motor_type:{
  required:[true,"Motor Type"]
  },
  from_flow_meter:{
  number:[true,"From Permanent Flow Meter"],
  min: 0
  },
  from_device:{
  number:[true,"From Device used to verify GPM"],
  min: 0
  }
 }
 
var validateForm = function(formID, rulesNames, rules){
 var rulesApplied = new Array();
 for(var i = 0; i<rulesNames.length; i++){
  var ruleName = rulesNames[i];
  rulesApplied[ruleName] = rules[ruleName];
 }
 var encapsulatedRules = new Array();
 encapsulatedRules['rules'] = rulesApplied;
 $('#'+formID).validate(encapsulatedRules);
}

var validateEvalTypeMethod = function(){
 var rulesNames = ["eval_type","eval_method"];
 validateForm("evalForm",rulesNames, rules);
}

var validateBeforeCalculation = function(){
 var calculationValidationRulesNames = 
  ["eval_type",
   "eval_method",
   "display_id",
   "eval_funding_sources",
   "eval_yr",
   "eval_month",
   "irr_sys_type",
   "acre",
   "crop_category",
   "nir_water_use",
   "actual_water_use"];
  validateForm("evalForm",calculationValidationRulesNames, rules);
 }
 
var evalTypeOnChange = function(){
        var eval_type = $('#eval_type').val();
//        if(eval_type == 3){
//         //Replacement Evaluation only have one evaluation method "Irrigation System Only"
//           $('#eval_method').html('<option value="irr">Irrigation System Only</option>');
//        }else{
//           $('#eval_method').html('<option value="irr">Irrigation System Only</option><option value="firm">FIRM</option>');
//        }
    $('#eval_method').html('<option value="irr">Irrigation System Only</option><option value="firm">FIRM</option>');
 };

var validateBeforeSubmit = function(){
 var calculationValidationRulesNames = 
   ["eval_type",
    "eval_method",
    "display_id",
    "eval_funding_sources",
    "eval_yr",
    "eval_month",
    "irr_sys_type",
    "acre",
    "crop_category",
    "nir_water_use",
    "actual_water_use",
    "county_id",
    "zip_code",
    "soil_type",
    "water_source",
    "tds",
    "ph",
    "pump_type",
    "has_flow_meter",
     "device_gpm",
     "motor_type",
     "from_flow_meter",
     "from_device"
    ];
  validateForm("evalForm",calculationValidationRulesNames, rules);
}
