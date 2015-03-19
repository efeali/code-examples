/**
 * Created by Iam on 2/6/15.
 */


var divs = document.querySelectorAll('div');
var numDivs = divs.length;

for(var i=0; i<numDivs; i++)
{
    divs.item(i).addEventListener('click',function(){event.stopPropagation(); removeElement(this);},false);
}


function removeElement(e)
{
    console.log(e);
    var parent = e.parentNode.removeChild(e);
}
