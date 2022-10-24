document.getElementById('start').addEventListener('click', function(){
       alert('test');
        chrome.app.window.current().close();
        
        
        
      });
      
storage.set({'copies': ['Your First Copy!']});
