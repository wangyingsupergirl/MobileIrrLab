

function getSelector(hiddeninputBoxID, elemName){
    var names = {
        dropdownlist:'dropdownlist'
        ,dropdownlistoption: 'dropdownlist option'
        ,dropdownlistselected: "dropdownlist option\:selected"
        ,addbutton: 'addbutton'
        ,errmsgfield: 'errmsgfield'
        ,displaylist:'displaylist'
    };
    var selector = "";
    if(elemName in names){
        selector = "#" + hiddeninputBoxID + "_" + names[elemName]
    }else{
        alert(elemName+" doesn't exist.");
    }
    return selector;
}

/*
 * Class HiddenInputBox
 * Methods:
 * 1.remove(id)
 * 2.add(id)
 * 3.exist()
 * 4.valueOf()
 */
function HiddenInputBox(hiddenInputBoxID){
 this.id = hiddenInputBoxID;
 this.selector = "#" + this.id;
 this.errorCode = new Array("Choose one isn't a valid choice","has been added","4 crops maximum");
}
/*
 * Remove option from hidden input
 */
HiddenInputBox.prototype.remove = function(id){ //id: option id
  var val = $(this.selector).val();
  var arr = val.split(',');
  val = '';
  for(var i=0; i < arr.length; i++){
      if(arr[i]!=id){
          val += ','+ arr[i];
      }
  }
  if(val.length > 1){
        val = val.substr(1,val.length-1);
  }
  $(this.selector).val(val);
}

/*
 * Check whether option has been added before
 */
HiddenInputBox.prototype.exist = function(id){
  var val = $(this.selector).val();
  var arr = val.split(',');
  val = '';
  for(var i=0; i < arr.length; i++){
      if(arr[i]==id){
          return true;
      }
  }
  return false;
}
/*
 * Add option to hidden input
 */
HiddenInputBox.prototype.add = function(id){
 if(id == 0||id == ""){
  return this.errorCode[0];
 }
 var val = $(this.selector).val();
 if(val==''){
    val = id;
 }else{
    if(this.exist(id)){
        return "Option " + id + " " + this.errorCode[1];
    }else{
        
          if(this.id=="crop_category"){
              var ids=val.split(",");
              if(ids.length==4){
                  return this.errorCode[2];
              }      
  }

        val += ',' + id;

    }
 }
 $(this.selector).val(val);
 return true;
}

/*
 * Return value of input box
 */
HiddenInputBox.prototype.valueOf = function(){
 return $(this.selector).val();
}

/*
 *Class DisplayList
 * Methods:
 * 1.getOptionID2Name(); initialize map option id to name. this member variable will be used by buildIcon()
 * 2.update(); update display list. It calls buildIcon(optionID)
 * 3.buildIcon(optionID); build an icon by option id in display list
 */
function DisplayList(hiddenInputBoxObject, imageURL){
 this.hiddenInputBoxObject = hiddenInputBoxObject;
 this.selector = getSelector(this.hiddenInputBoxObject.id,'displaylist');
 this.optionID2Name = this.getOptionID2Name();
 this.imageDir = imageURL;
}

/*
 * Update display list, based on the value of hidden input box.
 */
DisplayList.prototype.update = function(){
  var val = this.hiddenInputBoxObject.valueOf();
  if(val!=""){
   var arr = val.split(',');
   $(this.selector).html(""); //Clear preview content in display list, since append() is used. 
   for(var i=0; i < arr.length; i++){
    var optionID = arr[i];
    var link = this.buildIcon(optionID);
    $(this.selector).append(link);
  }
  }
}

/*
 * Build the removable icon for the selected option 
 */
DisplayList.prototype.buildIcon = function(optionID){
 
 var html ='<span class="removable_icon">'+
           '<span title="'+ this.optionID2Name[optionID] + '">' + optionID + 
           '</span>'+
           '<img class="remove_button" id="remove:'+ optionID +
           '"  title="Remove" src="' + 
           this.imageDir + '" alt="Remove">'+
           '</span>';
 var icon_link = $(html);
 var hiddenInputBoxObject = this.hiddenInputBoxObject;
 /*
  * Once the icon is clicked,
  * remove icon itself and update hidden input box 
  */
 icon_link.click(function(){
  $(this).remove();
  hiddenInputBoxObject.remove(optionID);
 });

 return icon_link;
}

/*
 * Get Map from option id to option name
 */
DisplayList.prototype.getOptionID2Name = function(){
 var selector = getSelector(this.hiddenInputBoxObject.id,'dropdownlistoption');
 var map = {};
 $(selector).each(
 function(){
  var id = $(this).val();
  var detail = $(this).text();
  map[id] = detail;
 })
 return map;
}

/*
 * Class MultipleChoicesList
 */
function MultipleChoicesList(id, imageURL){
 this.hiddenInputID = id;
 this.imageURL = imageURL
}

MultipleChoicesList.prototype.load = function(){
 var inputBox = new HiddenInputBox(this.hiddenInputID);
 var displayList = new DisplayList(inputBox,this.imageURL);
 displayList.update();
 // for event handler mc.add (call back function need environment)
 var mc  = this;
 var addButton = getSelector(this.hiddenInputID,'addbutton');
 //Add click event for add button
 $(addButton).click(function(){
    mc.add();
 });
}

MultipleChoicesList.prototype.add = function(){
 var inputBox = new HiddenInputBox(this.hiddenInputID);
 var displayList = new DisplayList(inputBox,this.imageURL);
 var selectedOptionID = getSelector(this.hiddenInputID,"dropdownlistselected");
 var errMsgID = getSelector(this.hiddenInputID,"errmsgfield");
 var optionID = $(selectedOptionID).val();
 var suc = inputBox.add(optionID);
 if(suc==true){
  $(errMsgID).html("");
  displayList.update();
 }else{
  $(errMsgID).html(suc);
 }
}

