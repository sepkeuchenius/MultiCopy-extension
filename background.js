//createContexts();

//function createContexts(){
  async function getCurrentTab() {
    let queryOptions = { active: true, currentWindow: true };
    let [tab] = await chrome.tabs.query(queryOptions);
    return tab;
  }


  chrome.commands.onCommand.addListener(async function(command) {
    if(command == "copy_to_clipboard"){
      currentTabId = await getCurrentTab()
      chrome.scripting.executeScript( {
        "target": {
          "tabId": currentTabId.id
        },
        func: () => {return window.getSelection().toString()}
      }, function(selection) {
        if(selection){
          selection = selection[0].result
          var newCopy = selection;
          //alert(newCopy)
          var storage = chrome.storage.local;
          storage.get('copies', function(result){
              var oldCopies = result.copies;
              if(!oldCopies){
                oldCopies = [];
              }
              oldCopies.push(newCopy);
              var storage = chrome.storage.local;
              //alert(oldCopies);
              //alert(storage);
              storage.set({'copies': oldCopies});
              chrome.notifications.create('Copy', {
                type:'basic',
                iconUrl: 'assets/icon_128_mc_2.png',
                title: 'Copy Saved!',
                message: 'The text selection was succesfully added to your MultiCopy Clipboard.'
  
              })
  
          })
        }
      });
      reloadAllContexts();
         }
  });

reloadAllContexts();
function reloadAllContexts(){
  chrome.contextMenus.removeAll();
    chrome.contextMenus.create({
      id:'paste',
      title: "Paste copy",
      contexts:["editable"],
    },
  pasteContext());
  chrome.contextMenus.create({
    id:'add',
    title: "Add copy: %s",
    contexts:["selection"],
  });
  chrome.contextMenus.create({
    id:'PasteAll',
    title: "Paste all copies",
    contexts:["editable"],
  });
}

function pasteContext(){
  var storage = chrome.storage.local;
    storage.get('copies', function(result){
      var gottenCopies = result.copies;
      newarray = gottenCopies.reverse();
      console.log(newarray);
      for(var i in newarray){
        chrome.contextMenus.remove('pastes,' + i + ','+ newarray[i]);
      }
      for(var i in newarray){
        chrome.contextMenus.create({
          title: newarray[i],
          parentId: 'paste',
          id: 'pastes,' + i  + ','+ newarray[i],
          contexts: ["editable"],
        })
      }
    });
}
chrome.storage.onChanged.addListener(function(changes, namespace) {
  reloadAllContexts();
});

chrome.runtime.onInstalled.addListener(function(details){
    if(details.reason == "install"){
      chrome.notifications.create('install', {
        type:'basic',
        iconUrl: 'assets/icon_128_mc_2.png',
        title: 'Welcome to MultiCopy!',
        message:  "Alt+C to copy. Right click menu to copy & paste!"

      })
    }
    else if(details.reason == "update"){
      chrome.notifications.create('update', {
        type:'basic',
        iconUrl: 'assets/icon_128_mc_2.png',
        title: 'MultiCopy has been updated.',
        message: "Right clicks and key-combinations are working again!"
      })
    }
});
chrome.notifications.onClicked.addListener(() => {
  chrome.tabs.create({
    "url": "https://chrome.google.com/webstore/detail/checkpoint/ioghepelnhpfiamjlgbcakmjmlnmmbcc"
  })
})
chrome.contextMenus.onClicked.addListener(async function(info, tab) {
    if(info.menuItemId=='add'){
        var storage = chrome.storage.local;
    var newCopy  = info.selectionText;

    storage.get('copies', function(result){
      var gottenCopies = result.copies;
      if(!gottenCopies){

        gottenCopies = [];
      }

      gottenCopies.push(newCopy);

      storage.set({'copies': gottenCopies});

     chrome.notifications.create('copy', {
        type: 'basic',
        iconUrl: 'assets/icon_128_mc.png',
        title: 'Copy Saved',
        message: "The text selection was succesfully added to your MultiCopy Clipboard."
     });

    });
       }
      else if(info.menuItemId.includes("pastes")){
        // chrome.notifications.create('update', {
        //   type:'basic',
        //   iconUrl: 'assets/icon_128_mc_2.png',
        //   title: 'Pasting is deprecated. Download our new Extension!',
        //   message: "Unfortunately Chrome updates have forced us to build a new extension. We've built a new, more elaborate version of MultiCopy."
        // })

        //paste a copy
        currentTab = await getCurrentTab()
        text = info.menuItemId.split(",")[2]
        pasteSelection(text, currentTab)
      
      }
      else if(info.menuItemId == "PasteAll"){
        currentTab = await getCurrentTab()
        chrome.storage.local.get('copies', function(result){
          pasteSelection(result.copies.join("\n"), currentTab)
        })
      }

});

function pasteSelection(text, tab){
  console.log(text)
  chrome.scripting.executeScript({
      target: {tabId:tab.id},
      func: insertSelection,
      args: [text]
  })
}
function insertSelection(text){
  console.log(text)
  document.activeElement.value += text;
}



// function reloadContext(){
//   //alert('falla')
//   try{
//     chrome.contextMenus.remove('paste', function() {
//   chrome.contextMenus.create({
//     id:'paste',
//     title: "Paste copy",
//     contexts:["editable"],
//   },
//   pasteContext());
//   // chrome.contextMenus.create({
//   //   title: 'test',
//   //   parentId: 'paste',
//   //   id: 'test',
//   //   contexts: ["editable"],
//   // });
//   }, function(){alert('callback!')});
//   }
//   catch(err){
//     chrome.contextMenus.create({
//       id:'paste',
//       title: "Paste copy",
//       contexts:["editable"],
//     },
//   pasteContext());
//   }
//   //pasteContext();
//
