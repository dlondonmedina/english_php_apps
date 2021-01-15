// Function to be used if there isn't passed to makeDocument();
function makeAFile() {

  if (!window.Blob) {
     alert('Your legacy browser does not support this action.');
     return;
  }

  var html, link, blob, url;
  // EU A4 use: size: 841.95pt 595.35pt;
  // US Letter use: size:11.0in 8.5in;
  html = tinymce.activeEditor.getContent();
  //html = window.docx.innerHTML;

  blob = new Blob(['\ufeff', html], {
    type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
  });
  url = URL.createObjectURL(blob);
  link = document.createElement('A');
  link.href = url;
  // Set default file name.
  // Word will append file extension - do not add an extension here.
  link.download = 'Document';
  document.body.appendChild(link);
  if (navigator.msSaveOrOpenBlob ) navigator.msSaveOrOpenBlob( blob, 'Document.docx'); // IE10-11
     else link.click();  // other browsers
  document.body.removeChild(link);
};

function loadFile(template, callback) {
  JSZipUtils.getBinaryContent(template, callback);
}

function makeDocument(template = null, myJSON = null) {

  if (!myJSON) {
    /* If we didn't pass a json object from db, then we use what's
    * in the tinymce editor.
    */
    makeAFile();
  } else {
    if (!template) {
      template = '/templates/template.docx';
    }
    loadFile(template, function(error, content) {
      if (error) {
        makeAFile();
        throw error
      };
      var zip = new JSZip(content);
      var doc = new Docxtemplater().loadZip(zip);
      // console.log(myJSON);
      // var flyertext = JSON.parse(myJSON);
      // console.log(flyertext);
      //alert(mySpacer);
      // clean line breaks and such
      var myObj = {};
      for (var key in myJSON) {
        if(myJSON.hasOwnProperty(key)) {
          var newVal = myJSON[key].replace(/[\r\n]+/g, '\n');
          myObj[key] = newVal.replace(/[<\/p>]+/g, '');
        }
      }
      var mySpacer = "";
      for (var i = 0; i < (11 - (myObj.description.length / 95)) / 2; i++) {
        mySpacer += "\n";
      };
      doc.setData({
        speaker: myObj.speaker,
        title: myObj.title,
        spacer1: mySpacer,
        description: myObj.description,
        spacer2: mySpacer,
        date: myObj.date,
        time: myObj.time,
        place: myObj.place
      });

      try {
        doc.render();
      }
      catch(error) {
        var e = {
          message: error.message,
          name: error.name,
          stack: error.stack,
          properties: error.properties,
        };
        console.log(JSON.stringify({error: e}));

        throw error;
      }
      var out = doc.getZip().generate({
        type:"blob",
        mimeType: "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
      });
      var docTitle = myObj.title.replace(/\s+/g, '');
      saveAs(out, docTitle + ".docx");
    });
  }


}
