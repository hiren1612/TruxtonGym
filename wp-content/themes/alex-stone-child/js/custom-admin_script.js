jQuery(document).ready(function(){
// alert("mittal");
// jQuery( document ).click( "#print", function() {
 
//   var divToPrint=document.getElementById('print_area');

//   var newWin=window.open('','Print-Window');

//   newWin.document.open();

//   newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

//   newWin.document.close();
// 	newWin.document.print();

//   setTimeout(function(){newWin.close();},10);

 
 
 
  
// });
// });
jQuery( "#print" ).click(function() {
//             var divContents = document.getElementById("print_area").innerHTML;  
//             var printWindow = window.open('','Print-Window');  
//             printWindow.document.write('<html><body>'+divContents+'</body></html>'); 
//             printWindow.document.close();  
//             printWindow.print(); 
	var is_chrome = function () { return Boolean(window.chrome); }
	var divContents = document.getElementById("print_area").innerHTML;  
       var printWindow = window.open('','Print-Window');
       printWindow.document.write('<html><head><title>Print candidate Content</title>');  
       printWindow.document.write('</head><body>');  
       printWindow.document.write(divContents);  
       printWindow.document.write('</body></html>');  
       printWindow.document.close();  
//        printWindow.print();
	if(is_chrome) 
{
   printWindow.print();
   setTimeout(function(){printWindow.close();}, 10000); 
   //give them 10 seconds to print, then close
}else{
	 printWindow.print();
    printWindow.close();
}
       }); 
});
