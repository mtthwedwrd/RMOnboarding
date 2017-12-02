var _doc = $(document);
  var _page = _doc.find("section");
  var data_url = "";
  var _topic_image = "";
  var _topic = "";
  var _user_rate = 0;
  var _journal = 0;
  var _user = _doc.find("#user_tab");
  var _searching = "";
  var _search_table = null;
  var _regex_highlighter = null;
  var formodal = false;
  if(!!localStorage.getItem("id")){
    _user.removeClass('hidden').find("#user_name").html("Hi "+localStorage.getItem("fname")+"<span class=\"caret\"></span>");
  }

  function myChart(){
    window.onload = function() {
            var ctx1 = document.getElementById("canvas").getContext("2d");
            window.myLine = new Chart(ctx1, config1);
        
            var ctx = document.getElementById("mycanvas").getContext("2d");
            window.myDoughnut = new Chart(ctx, config);
            
            var ctx2 = document.getElementById("analytics").getContext("2d");
            window.myLine = new Chart(ctx2, config2);            

        
            
        };
      
        var MONTHS = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var config2 = {
            type: 'line',
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [{
                    label: "My First dataset",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor()
                    ],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'Analytics'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Month'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };
      
        var MONTHS = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var config1 = {
            type: 'line',
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [{
                    label: "My First dataset",
                    backgroundColor: window.chartColors.orange,
                    borderColor: window.chartColors.orange,
                    data: [
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor(),
                        randomScalingFactor()
                    ],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'Analytics'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Month'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };
        

        var config = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    
                    60,
                    30,
                    10,
                  
                ],
                backgroundColor: [
                    
                    window.chartColors.orange,
                    window.chartColors.red,
                    window.chartColors.green,
                    
                ],
                label: 'Dataset 1'
            }],
            labels: [
                
                "Car Loan",
                "House Loan",
                "Savings",
                
            ]
        },
        options: {
            responsive: true,
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                //text: 'Chart.js Doughnut Chart'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    };
      
  }


function register(){
    var sign_up = _page.find("#sign_up");
    var valid = true;
    sign_up.find(".form-group").removeClass('has-error').find(".help-block").remove();
    data = {};
    sign_up.find("input").each( function(index, element) {
      element = sign_up.find(element);
      if(element.attr('placeholder')!="Middle Name"){
        if(element.val().trim()==""){
          errorMessage(element,element.attr('placeholder')+" is required");
          valid = false;
        }else{
          data[element.attr('name')]=element.val().trim();
        }
      }else{
        data[element.attr('name')]=element.val().trim();
      }
    });
    
   if(valid){
      $.post(data_url+"/_user.php?action=register",data, function(data, textStatus, xhr) {
          data = JSON.parse(data);
          if(data.result=="already"){
            errorMessage(sign_up.find("input[name='id']"),"ID already exist");
          }
          if(data.result=="success"){
            toastr.clear();
            toastr['success']("Successfully Registered! Please wait for Admin Approval");
          }
      });
    }
  }


function login(){
    var login = _page.find("#login");
    var valid = false;
    login.find(".form-group").removeClass('has-error').find(".help-block").remove();
    data = {};
    login.find("input").each( function(index, element) {
      element = login.find(element);
      if(element.val().trim()!=""){
        data[element.attr('name')]=element.val().trim();
        valid = true;
      }
    });
    if(valid){
      $.post(data_url+"../_user.php?action=login",data, function(data, textStatus, xhr) {
          data = JSON.parse(data);
          if(data.result=="success"){
            createUser(data);
          }else{
            login.find("input[name='id']").parent().prepend("<p class='help-block' style='color:red;'><b>Either User not Allowed or Password Incorrect</b></p>");
          }
      });
    }
  }