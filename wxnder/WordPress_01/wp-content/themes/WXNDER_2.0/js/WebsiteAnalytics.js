var data = [];
var colors =  ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"];
$(document).ready(function(){
  loadData()
})
function loadData(){
  $('tr').each(function(){
    var cells = $(this).children();
    var row = {
      'ID': cells[0].textContent,
      'Tijd': new Date(cells[1].textContent),
      'Page': cells[2].textContent,
      'Query': cells[3].textContent,
      'Class': cells[4].textContent,
      'AttrID': cells[5].textContent,
      'Text': cells[6].textContent,
    }
    data.push(row);
  })

  $('table').remove()
  data.splice(0,1);
  analyze()
}
var todaystart = new Date();
todaystart.setHours(7);
todaystart.setMinutes(0);
todaystart.setSeconds(0);
var graphs =[
  {
    'name': "Gebruikers per dag",
    'names': ['PASEN', 'BOX', 'GO', 'CADEAU'],
    'unit': 1000*3600*24,
    'labels':[01,02,03,04,05,06,07,08],
    'start': new Date('Sat May 1 2021 00:00:00 GMT+0100'),
    'measurements': [['landers', 'pasen'], ['landers', 'box'], ['landers', 'go'], ['landers', 'cadeau']]
  },
  {
    'names': ['PASEN', 'BOX', "GO", 'CADEAU'],
    'name': "Gebruikers vandaag",
    'unit': 1000*3600,
    'labels':["07:00", "08:00", "09:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00","23:00"],
    'start': todaystart,
    'measurements':[['landers', 'pasen'], ['landers', 'box'],['landers', 'go'], ['landers', 'cadeau']]
  }
  // {
  //   'name': 'Gebruiker PASEN vanaf 23 maart per uur',
  //   'unit': 1000*3600*12,
  //   'labels':[1*12,2*12,3*12,4*12,5*12,6*12,7*12,8*12,9*12,10*12,11*12,12*12,13*12,14*12,15*12,16*12],
  //   'start': new Date('Tue Mar 23 2021 14:16:16 GMT+0100'),
  //   'measurements': ['landers', 'pasen'],
  // },
]
var charts = [
  {
    'name': 'Gebruikers per pagina',
    'type': 'fractions',
    'options':{
      'fractions': ['go', 'box', 'pasen'],
      'withins': ['gebruikers'],
    }
  },
  {
    'name': 'Interactie (Kliks)',
    'type': 'fractions',
    'options':{
      'fractions': ['gebruikers','interactie'],

    }
  },
  {
    'name': 'Bronnen',
    'type': 'fractions',
    'options':{
      'fractions': ['direct','fb', 'header'],

    }
  },
  {
    'name': 'Hoever in bestelproces PASEN',
    'type': 'fractions',
    'options':{
      'fractions': ['samenstelling', 'route', 'gegevens'],
      'withins': ['pasen']

    }
  },
  {
    'name': 'Bestelproces begonnen',
    'type': 'fractions',
    'options':{
      'fractions': ['gebruikers','bestelProcesBegonnen'],

    }
  },
]
var attributesPerData = {
  "gebruikers":{},
  "landers":{
    'in':{
      "Class": ['start']
    }
  },
  "bestelProcesBegonnen":{
    "in": {
      "Class": ['counter_button' ,'counter_button neg', 'active']
    }
  },
  'pasen':{
    'in': {
      'Page': ['pasen']
    }
  },
  'box':{
    'in':{
      'Page': ['box']
    }
  },
  'go':{
    'in':{
      'Page': ['go']
    }
  },
  'cadeau':{
    'in':{
      'Page': ['cadeau']
    }
  },
  'interactie':{
    'in':{},
    'out': {
      'AttrID': ['burger_menu_button', 'header_image'],
      'Class': ['start']
    }
  },
  'pic':{
    'in':{
      'Class':['pic_left']
    }
  },
  'fb':{
    'has':{
      'Query': ['?fb']
    }
  },
  'header':{
    'has':{
      'Query': ['?src']
    }
  },
  'direct':{
    'in':{
      'Query': ['']
    },
    'out':{
      'ID':['13086', '7126', '42583','63973','59898']
    }
  },
  'route':{
    'in':{
      'Text': ['Gegevens >']
    }
  },
  'samenstelling':{
    "in": {
      "Class": ['counter_button' ,'counter_button neg', 'active']
    }
  },
  'gegevens':{
    'in':{
      'Text': ['Levering >']
    }
  }
}
function getUserFractions(pars, withins=[]){
  var distinctData = {}
  var fractions = {}
  for(var par of pars){
    var users = getData([par].concat(withins), 'ID', true);
    distinctData[par] = users;
  }
  for(var parName in distinctData){
    for(var parName2 in distinctData){
      if(parName == parName2){continue}
      distinctData[parName] = division(distinctData[parName], distinctData[parName2]);
    }
    fractions[parName] = u(distinctData[parName]).length;
  }
  return fractions;
}
function analyze(){
  post('last-activity', getLastActivity());
  for(var i in charts){
    var charti = charts[i]
    var canvas  = $('<canvas>').attr('width', 400).attr('height', 400);
    var datasets = getUserFractions(charti.options.fractions, charti.options.withins)
    var total = 0;
    for(var i in datasets){
      total+=datasets[i];
    }
    var chart = new Chart(canvas, {
      type: 'pie',
      data:{
        labels: Object.keys(datasets),
        datasets: [{data:Object.values(datasets), backgroundColor: colors}],
      },
      options: {
       responsive: true,
       title:{
           display: true,
           text: charti.name + ' ('+total+')',
       }
     }
    })
  $('#data-results').append(canvas)
  }

  for(var i in graphs){
    var graphi = graphs[i];
    var canvas  = $('<canvas>').attr('width', 400).attr('height', 400);
    var start = graphi.start.getTime();
    var unit = graphi.unit;
    var datasets = [];
    var boxes = [];
    var now = new Date().getTime();
    var n = start;
    while(n < now){
      boxes.push(0)
      n += unit;
    }
    for(var j in graphi.measurements){
      var datasetj = [...boxes];
      console.log(datasetj)
      var measurements = graphi.measurements[j];
      console.log(measurements)
      var dataset = getData(measurements, false, true);
      console.log(dataset)
      for(var i in dataset){
        var time = dataset[i].Tijd.getTime();
        var timediff = time - start;
        if(timediff <  0){continue}
        var box = Math.floor(timediff/unit);
        datasetj[box]++
      }
      datasets.push(datasetj)
    }
    var myLineChart = new Chart(canvas, {
    type: 'bar',
    data: {
      labels: graphi.labels,
      datasets:
      datasets.map(function(dataset, index){
        console.log(dataset)
        return {
          data: dataset,
          borderColor: colors[index],
          backgroundColor: colors[index],
          fill:true,
          label: graphi.names[index]
        }

      })

    },
    options: {
     responsive: true,
     title:{
         display: true,
         text: graphi.name,
     }
    }
    // options: options
    });

    $('#data-results').append(canvas)
  }
}
function getLastActivity(){
  var now = new Date().getTime();
  var last = data[data.length - 1].Tijd.getTime();
  var elapsedMinutes = Math.round((now - last)/(1000*60));
  return elapsedMinutes;
}
function getData(pars, identifier, unique=false){
  var returnData = data;
  for(var par of pars){
    var att = attributesPerData[par];
    var totalData = [];
    if(att.in && Object.keys(att.in).length > 0){
      for(var type in att.in){
        var values = att.in[type];
        for(var value of values){
          var addData = returnData.filter(function(row){return row[type] == value});
          totalData = totalData.concat(addData);
        }
      }
      totalData = u(totalData);
    }
    else{
      totalData = returnData;
    }
    returnData = intersect(totalData, returnData);

    totalData = [];
    if(att.has && Object.keys(att.has).length > 0){
      for(var type in att.has){
        var values = att.has[type];
        for(var value of values){
          var addData = returnData.filter(function(row){return row[type].indexOf(value) > -1});
          totalData = totalData.concat(addData);
        }
      }
      totalData = u(totalData);
    }
    else{
      totalData = returnData;
    }
    returnData = intersect(totalData, returnData);


    if(att.out && Object.keys(att.out).length > 0){
      for(var type in att.out){
        var values = att.out[type];
        for(var value of values){
          var returnData = returnData.filter(function(row){return row[type] != value});
        }
      }
    }


  }

  if(unique){
    returnData = uRow(returnData)
  }
  if(identifier){
    returnData = project(returnData, identifier)
  }
  return returnData;
}
function getBestelprocesBegonnen(page){
  var useData = data;
  if(page){
    useData = useData.filter(function(row){return row.Page == page});
  }
  return u(useData.filter(function(row){return row.Class == 'counter_button' || row.Class == 'counter_button neg' || row.Class == 'active'}).map(function(row){return row.ID}));
}
function post(id, value){
  $('#'+id).text(value);
}
function u(list){
  return $.unique(list)
}
function uRow(x, identifier = 'ID'){
  var xI = x.map(function(row){return row[identifier]});
  return x.filter(function(row, index){return xI.indexOf(row[identifier]) == index});
}
function intersect(x,y,identifier='ID'){
  yI = y.map(function(row){return row[identifier]});
  return x.filter(function(row){return yI.indexOf(row[identifier]) > -1});
}
function project(x, identifier){
  return x.map(function(row){return row[identifier]});
}
function division(x,y){
  return x.filter(function(el){return y.indexOf(el) == -1});
}
