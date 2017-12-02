  <?php include "_page_validator.php" ?>

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

  function topic(){
      $.post(data_url+'/_topic.php?action=select', function(data) {
        data = JSON.parse(data);
        var topic_list = _doc.find("#topic").empty();
        data.forEach( function(element, index) {
            topic_list.append('<li><a href="#'+element.topic+'" onclick="loadpage(\'topic\')">'+element.topic+'</a></li>')
        });
      });
  }



  function banner(){
      $.post(data_url+'/_banner.php?action=select', function(data) {
        data = JSON.parse(data);
        var bane_list = _doc.find("#myCarousel");
        var item = bane_list.find("#banner_item").empty();
        var indicator = bane_list.find("#banner_indicator").empty();
        data.forEach( function(element, index) {
              indicator.append('<li data-target="#myCarousel" data-slide-to="'+index+'"></li>')
              item.append('<div class="item"><img src="assets/upload/'+element.image+'" alt="Los Angeles"></div>');
        });
        indicator.children().first().addClass('active');
        item.children().first().addClass('active');
      });
  }



  function change_credentials(is_login){
    var cred = _doc.find("#credentials");
    if(is_login){
      cred.find("#login").removeClass('hidden');
      cred.find("#sign_up").addClass('hidden');
    }else{
      cred.find("#login").addClass('hidden');
      cred.find("#sign_up")[0].reset();
      cred.find("#sign_up").find(".has-error").removeClass('has-error').find('.help-block').remove();
      cred.find("#sign_up").removeClass('hidden');
    }
  }



  function load_library(){
    _page.find("#library").DataTable( { 
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select class="form-control footer-filter" ><option value="">All</option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        },
        "responsive": true,
        "orderMulti": true,
        "order": [],
        "ajax": data_url+"/_library.php?action=select",
        "columns": [
            { "data": "title", render: function(data,type,full){
              return "<a href='#file&id"+full['id']+"' onclick='loadpage(\"file\");addViews("+full['id']+");'>"+data+"</a>";
            }},
            <!--{ "data": "proponent" },-->
            { "data": "stat_prop" },
            { "data": "topic" },
            
            <!--{ "data": "date_published"},-->
            { "data": "year" },
            { "data": "ratings_render",render: function(data,type,full){
              var rate = parseFloat(full['ratings']);
              var html = '<span class="hidden">'+data+'</span><div class="rating-container custom-rating-xs rating-animate"><div class="rating-stars">'+
                            '<span class="empty-stars">'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                            '</span>'+
                            '<span class="filled-stars" style="width: '+(rate/0.05)+'%;">'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                            '</span>'+
                          '</div></div>';
              return html;
            }}
        ]
    });
  }



  function load_recent(){
    _page.find("#recent").DataTable( { 
        "responsive": true,
        "ajax": {
          "url": data_url+"/_library.php?action=select",
          "type": "POST",
          "data": {"recent":true}
        },
        "paging": false,
        "filter":false,
        "bSort": false,
        "orderMulti": true,
        "info": false,
        "order": [],
        "columns": [
            { "data": "title" , render: function(data,type,full){
              return "<a href='#file&id"+full['id']+"' onclick='loadpage(\"file\");addViews("+full['id']+");'>"+data+"</a>";
            }},
            <!--{ "data": "proponent" },-->
            { "data": "stat_prop" },
            { "data": "topic" },
            
            <!--{ "data": "date_published"},-->
            { "data": "year" },
            { "data": "ratings_render",render: function(data,type,full){
              var rate = parseFloat(full['ratings']);
              var html = '<span class="hidden">'+data+'</span><div class="rating-container custom-rating-xs rating-animate"><div class="rating-stars">'+
                            '<span class="empty-stars">'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                            '</span>'+
                            '<span class="filled-stars" style="width: '+(rate/0.05)+'%;">'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                            '</span>'+
                          '</div></div>';
              return html;
            }}
        ]
    });
  }



  function load_topic(_topic){
    _page.find("#topics").DataTable( { 
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select class="form-control footer-filter" ><option value="">All</option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        },
        "responsive": true,
        "order": [],
        "orderMulti": true,
        "ajax": {
          "url": data_url+"/_library.php?action=select",
          "type": "POST",
          "data": {"topic":_topic}
        },
        "columns": [
            { "data": "title" , render: function(data,type,full){
              return "<a href='#file&id"+full['id']+"' onclick='loadpage(\"file\");addViews("+full['id']+");'>"+data+"</a>";
            }},
            <!--{ "data": "proponent" },-->
            { "data": "stat_prop" },
            { "data": "topic" },
            <!--{ "data": "date_published"},-->
            { "data": "year" },
            { "data": "ratings_render",render: function(data,type,full){
              var rate = parseFloat(full['ratings']);
              var html = '<span class="hidden">'+data+'</span><div class="rating-container custom-rating-xs rating-animate"><div class="rating-stars">'+
                            '<span class="empty-stars">'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                            '</span>'+
                            '<span class="filled-stars" style="width: '+(rate/0.05)+'%;">'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                            '</span>'+
                          '</div></div>';
              return html;
            }}
        ]
    });
  }

  function load_my_journals(){
    _page.find("#my_journals").DataTable( { 
        "responsive": true,
        "orderMulti": true,
        "order": [],
        "ajax": {
          "url": data_url+"/_library.php?action=my_journal",
          "type": "POST",
          "data": {"id":localStorage.getItem("id")}
        },
        "columns": [
            { "data": "title", render: function(data,type,full){
              var html= "<a href='#file&id"+full['id']+"' onclick='loadpage(\"file\");addViews("+full['id']+");'>"+data+"</a>"+'<button class="btn btn-xs btn-success" style="position: absolute;left: -24px;" title="Request for edit" onclick="request_for_edit('+full['id']+');"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>';
              if(full['is_enabled']==0){
                html = "<a href='#edit&id"+full['id']+"' onclick='loadpage(\"upload_file\");addViews("+full['id']+");'>"+data+"</a>";
              }
              return html;
            }},
            { "data": "stat_prop" },
            { "data": "year"},
            { "data": "is_enabled",render: function(data,type,full){
              var html = "<span style='color:green;'>PUBLISHED</span>";
              if(data==0&&full['had_published']==0){
                html = "PENDING";
              }else if(data==0){
                html = "<span style='color:red;'>DISABLED</span>";
              }
              return html;
            }},
            { "data": "ratings_render",render: function(data,type,full){
              var rate = parseFloat(full['ratings']);
              var html = '<span class="hidden">'+data+'</span><div class="rating-container custom-rating-xs rating-animate"><div class="rating-stars">'+
                            '<span class="empty-stars">'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                            '</span>'+
                            '<span class="filled-stars" style="width: '+(rate/0.05)+'%;">'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                            '</span>'+
                          '</div></div>';
              return html;
            }}
        ]
    });
  }
function load_reference(reference){
    _page.find("#reference").DataTable( { 
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select class="form-control footer-filter" ><option value="">All</option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        },
        "responsive": true,
        "orderMulti": true,
        "order": [],
        "ajax": {
          "url": data_url+"/_library.php?action=file_reference",
          "type": "POST",
          "data": {"id":localStorage.getItem("id"),journal_ids:reference}
        },
        "columns": [
            { "data": "title", render: function(data,type,full){
              return "<a href='#file&id"+full['id']+"' onclick='formodal=true;_page.find(\"#details_modal\").modal(\"hide\");addViews("+full['id']+");'>"+data+"</a>";
            }},
            <!--{ "data": "proponent" },-->
			{ "data": "stat_prop" },
            { "data": "topic" },
            { "data": "year" },
            <!--{ "data": "date_published"},-->
            { "data": "ratings_render",render: function(data,type,full){
              var rate = parseFloat(full['ratings']);
              var html = '<span class="hidden">'+data+'</span><div class="rating-container custom-rating-xs rating-animate"><div class="rating-stars">'+
                            '<span class="empty-stars">'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                            '</span>'+
                            '<span class="filled-stars" style="width: '+(rate/0.05)+'%;">'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                            '</span>'+
                          '</div></div>';
              return html;
            }}
        ]
    });
  }
  function load_citation(citation){
    _page.find("#citation").DataTable( { 
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select class="form-control footer-filter" ><option value="">All</option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        },
        "responsive": true,
        "orderMulti": true,
        "order": [],
        "ajax": {
          "url": data_url+"/_library.php?action=citation",
          "type": "POST",
          "data": {"id":localStorage.getItem("id"),journal_ids:citation}
        },
        "columns": [
            { "data": "title", render: function(data,type,full){
              return "<a href='#file&id"+full['id']+"' onclick='formodal=true;_page.find(\"#details_modal\").modal(\"hide\");addViews("+full['id']+");'>"+data+"</a>";
            }},
            <!--{ "data": "proponent" },-->
			{ "data": "stat_prop" },
            { "data": "topic" },
            
            <!--{ "data": "date_published"},-->
            { "data": "year" },
            { "data": "ratings_render",render: function(data,type,full){
              var rate = parseFloat(full['ratings']);
              var html = '<span class="hidden">'+data+'</span><div class="rating-container custom-rating-xs rating-animate"><div class="rating-stars">'+
                            '<span class="empty-stars">'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                            '</span>'+
                            '<span class="filled-stars" style="width: '+(rate/0.05)+'%;">'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                            '</span>'+
                          '</div></div>';
              return html;
            }}
        ]
    });
  }

  function load_search(){
    _search_table = _page.find("#search").DataTable( { 
        "pageLength": 25,
        "responsive": true,
        "orderMulti": true,
        "order": [],
        "bFilter":false,
        "bLengthChange": false,
        "ajax": {
          "url": data_url+"/_library.php?action=search",
          "type": "POST",
          "data": function ( d ) {
                d.search = _searching;
        }},
        "columns": [
            { "data": "title", render: function(data,type,full){
              
              return "<a href='#file&id"+full['id']+"' onclick='loadpage(\"file\");addViews("+full['id']+");'>"+data.replace(_regex_highlighter,"<b><i>"+_searching.toUpperCase()+"</i></b>")+"</a>";
            }},
            { "data": "abstract", render:$.fn.dataTable.render.ellipsis( 75, true )},
            
            { "data": "stat_prop", render: function(data,type,full){
              return data.replace(_regex_highlighter,"<b><i>"+_searching.toUpperCase()+"</i></b>");
            }}, 
            { "data": "topic", render: function(data,type,full){
              return data.replace(_regex_highlighter,"<b><i>"+_searching.toUpperCase()+"</i></b>");
            }},
            { "data": "year", render: function(data,type,full){
              return data.replace(_regex_highlighter,"<b><i>"+_searching.toUpperCase()+"</i></b>");
            }},
            { "data": "ratings_render",render: function(data,type,full){
              var rate = parseFloat(full['ratings']);
              var html = '<span class="hidden">'+data+'</span><div class="rating-container custom-rating-xs rating-animate"><div class="rating-stars">'+
                            '<span class="empty-stars">'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star-empty"></i></span>'+
                            '</span>'+
                            '<span class="filled-stars" style="width: '+(rate/0.05)+'%;">'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                              '<span class="star"><i class="glyphicon glyphicon-star"></i></span>'+
                            '</span>'+
                          '</div></div>';
              return html;
            }},
            { "data": "tags", render: function(data,type,full){
              return data.replace(_regex_highlighter,"<b><i>"+_searching.toUpperCase()+"</i></b>");
            }}, 
        ],
    });
  }
  function addViews(id){
    $.post(data_url+"/_library.php?action=addViews",{id:id}, function(data){});
  }
  function _search(){
    _searching = $("#search_bar").val();
    _regex_highlighter = new RegExp(_searching, "gi");
    loadpage('search');
  }
  function _search_inside(){
    _searching = _page.find("#searching").val();
    _regex_highlighter = new RegExp(_searching , "gi");
    _search_table.ajax.reload();
  }
  function search_keyup(event,is_search){
   if(event.which==13){
      if(is_search){
        _search_inside();
      }else{
        _search();
      }
   }
  }



  function loadpage(page){
    _page.addClass('loader').load(page+".php").removeClass('loader').unbind('ready').ready(function(){
    	_doc.scrollTop(0).find("script").remove();
    });
    loadpage_active_state(page!="home_in"?page:"home");
  }



  function loadpage_active_state(page){
    localStorage.setItem("page",page);
    try {
      _doc.find("#"+page+"_tab").addClass('active').siblings().removeClass('active');
    } catch(e) {}
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
    if(!isEmail(sign_up.find("input[name='email']").val())&&valid){
      errorMessage(sign_up.find("input[name='email']"),"Please use valid email");
      valid = false;
    }
    if(sign_up.find("input[name='password']").val()!=sign_up.find("input[name='confirm']").val()&&valid){
      errorMessage(sign_up.find("input[name='confirm']"),"Passwords didn't match");
      valid = false;
	}
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
  

  function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
  }
  function errorMessage(elem,msg){
      elem.after("<p class='help-block'>"+msg+"</p>").parent().addClass('has-error');
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
      $.post(data_url+"/_user.php?action=login",data, function(data, textStatus, xhr) {
          data = JSON.parse(data);
          if(data.result=="success"){
            createUser(data);
          }else{
            login.find("input[name='id']").parent().prepend("<p class='help-block' style='color:red;'><b>Either User not Allowed or Password Incorrect</b></p>");
          }
      });
    }
  }

  function createUser(data){
    localStorage.setItem("token",data.token);
    localStorage.setItem("id",data.id);
    localStorage.setItem("fname",data.fname);
    
    localStorage.setItem("lname",data.lname);
    _user.removeClass('hidden').find("#user_name").html("Hi "+data.fname+"<span class=\"caret\"></span>");
    loadpage("home_in");
  }
  function logout(){
    localStorage.removeItem("token");
    localStorage.removeItem("id");
    localStorage.removeItem("fname");
    
    localStorage.removeItem("lname");
    localStorage.removeItem("page");
    localStorage.removeItem("done");
    _user.addClass('hidden').find("#user_name").html("<span class=\"caret\"></span>");
    loadpage("home");
  }

  function sendRating(elem){
    var rate = (elem.value==""?0:parseInt(elem.value))-_user_rate;
    var rate_count = "";
    if(_user_rate==0&&rate>0){
      rate_count = "+1";
    }else if (_user_rate>0&&_user_rate+rate==0){
      rate_count = "-1";
    }
    $.post(data_url+"/_rate.php",{rate:rate,journal_id:_journal,rate_count:rate_count,new_rate:elem.value,user_id:localStorage.getItem('id')}, function(data, textStatus, xhr) {
        if(data=="1"){
          _user_rate = elem.value;
          toastr['success']("Thank You"); 
          _page.find("#_download").unbind('click').attr("href",data_url+"/_download.php?token="+localStorage.getItem("token")+"&id="+_page.find(elem).data('id'));
          window.location=window.location.origin+data_url+"/_download.php?token="+localStorage.getItem("token")+"&id="+_page.find(elem).data('id');
          _page.find("#rate_me").modal("hide");
        }
    });
  }

  function user_edit(element){
    if(element.childNodes[2].nodeValue.trim()=="Edit"){
      element.childNodes[2].nodeValue = " Cancel";
      element.nextElementSibling.classList.remove("hidden");
    }else{
      element.childNodes[2].nodeValue = " Edit";
      element.nextElementSibling.reset();
      var form = _page.find(element.nextElementSibling).addClass('hidden');
      form.find(".form-group").removeClass('has-error').find(".help-block").remove();
      if(form.find("#ckeditor_skills").length>0){
         CKEDITOR.instances.ckeditor_skills.setData("");
      }else if(form.find("#ckeditor_education").length>0){
         CKEDITOR.instances.ckeditor_education.setData("");
      }
    }
      
    
  }
  function profilepic(element){
    var file = element.files[0];
    var fileType = file["type"];
    var ValidImageTypes = ["image/jpg", "image/jpeg", "image/png"];
    if ($.inArray(fileType, ValidImageTypes) < 0) {
        toastr['error']("Invalid file!"); 
         return false;
    }
    var form_data = new FormData();                  
    form_data.append('file', file);                  
    form_data.append('token', localStorage.getItem("token"));                         
    $.ajax({
                url: data_url+"/_user_profile.php?action=profile", // point to server-side PHP script 
                dataType: 'text',  // what to expect back from the PHP script, if anything
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(data){
                    data = JSON.parse(data);
                    if(data.result=="success"){
                      _page.find("#user_profile_pic").attr("src",data.img);
                      toastr['success']("Successfully Changed"); 
                    }
                }
     });
  }
  function edit_user_information(element){
    var valid = true;
    var form = _page.find(element).parent();
    form.find(".form-group").removeClass('has-error').find(".help-block").remove();
    data = {};
    data["token"] = localStorage.getItem("token");
    form.find("input").each( function(index, element) {
      element = form.find(element);
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
       $.post(data_url+"/_user_profile.php?action=edit&data="+form.attr("id").split("_")[1],data, function(data, textStatus, xhr) {
          data = JSON.parse(data);
          if(data.result=="success"){
            if(typeof data.fname!="undefined"){
              information.find("#name").html(data.fname+" "+data.mname+" "+data.lname).siblings('a[onclick=\'user_edit(this);\']').click();
            }else if(typeof data.birthday!="undefined"){
              information.find("#bday").html(data.birthday==""?"click edit to add":data.birthday).siblings('a[onclick=\'user_edit(this);\']').click();
            }else if(typeof data.track!="undefined"){
              information.find("#track").html(data.track==""?"click edit to add":data.track).siblings('a[onclick=\'user_edit(this);\']').click();
            }else if(typeof data.address!="undefined"){
              information.find("#address").html(data.address==""?"click edit to add":data.address).siblings('a[onclick=\'user_edit(this);\']').click();
            }else if(typeof data.email!="undefined"){
              information.find("#email").attr('href','mailto:'+data.email).html(data.email==""?"click edit to add":data.email).siblings('a[onclick=\'user_edit(this);\']').click();
            }else if(typeof data.phone!="undefined"){
              information.find("#phone").html(data.phone==""?"click edit to add":data.phone).siblings('a[onclick=\'user_edit(this);\']').click();
            }
            toastr.clear();
            toastr['success']("Successfully Changed");
          }
      });
    }
  }
  
  function edit_password(element){
    var valid = true;
    var form = _page.find(element).parent();
    form.find(".form-group").removeClass('has-error').find(".help-block").remove();
    data = {};
    data["token"] = localStorage.getItem("token");
    var current_password = form.find("input[name='current_password']");
    var new_password = form.find("input[name='new_password']");
	var confirm_password = form.find("input[name='confirm_password']");
	form.find("input").each( function(index, element) {
      element = form.find(element);
      if(element.val().trim()==""){
        errorMessage(element,element.attr('placeholder')+" is required");
        valid = false;
      }else{
        data[element.attr('name')]=element.val().trim();
      }
    });
	if(confirm_password.val()!=new_password.val()){
		valid = false;
		errorMessage(confirm_password,"Passwords don't match.");
	}
    if(valid){
	data['id']=localStorage.getItem("id");
       $.post(data_url+"/_user_profile.php?action=edit&data="+form.attr("id").split("_")[1],data, function(data, textStatus, xhr) {
          data = JSON.parse(data);
          if(data.result=="success"){
            localStorage.setItem('token',data.token);
			information.find("#password").siblings('a[onclick=\'user_edit(this);\']').click();
            toastr.clear();
            toastr['success']("Successfully Changed");
          }else{
			errorMessage(current_password,"Invalid Password.");
		  }
      });
    }
  }
  
  function request_for_edit(id){
      $.post(data_url+"/_user.php?action=request",{id:id,token:localStorage.getItem("token")}, function(data) {
          console.log(data);
          data = JSON.parse(data);
          toastr.clear();
          toastr['success']("Request Sent");  
      });
  }

  function edit_user_information_editor(element,id){
    var form = _page.find(element).parent();
    data= {};
    data[id.split("_")[1]]=CKEDITOR.instances[id].getData();
    data["token"] = localStorage.getItem("token");
    $.post(data_url+"/_user_profile.php?action=edit&data="+form.attr("id").split("_")[1],data, function(data, textStatus, xhr) {
        data = JSON.parse(data);
        if(data.result=="success"){
          if(typeof data.education!="undefined"){
              information.find("#education").html(data.education==""?"click edit to add":data.education).siblings('a[onclick=\'user_edit(this);\']').click();
          }else if(typeof data.skills!="undefined"){
            information.find("#skills").html(data.skills==""?"click edit to add":data.skills).siblings('a[onclick=\'user_edit(this);\']').click();
          }
          toastr.clear();
          toastr['success']("Successfully Changed");
        }
    });
  }

  function othertopic(element){
  	if(element.value=="other"){
  		element.nextElementSibling.classList.remove("hidden");
  	}else{
  		element.nextElementSibling.classList.add("hidden");
  	}
  }

  function addsubdocument(){
  	var id = new Date().getTime()
  	var documentation = _page.find("#documentation");
  	_ckeditor_ids.push("ckeditor_"+id);
  	var html = '<div class="form-group col-md-12" id="ckeditor_'+id+'">'+
  				'<button type="button" class="btn btn-xs btn-danger pull-right" onclick="this.parentNode.remove();delete _ckeditor_ids[\''+"ckeditor_"+id+'\']"><i class="fa fa-times" aria-hidden="true"></i></button>'+
	    		'<label for="tags">Title:</label><br>'+
	    		'<input type="text" class="form-control col-md-12" id="_ckeditor_title_'+id+'" name="title_'+id+'" placeholder="Title">'+
	    		'<textarea class="sub_content" id="_ckeditor_'+id+'"></textarea>'+
	  		   '</div>';
	documentation.append(html);
	documentation.find('#_ckeditor_title_'+id).focus();
	var editor = CKEDITOR.replace( '_ckeditor_'+id, {
			filebrowserBrowseUrl: '/assets/ckfinder/ckfinder.html',
			filebrowserUploadUrl: '/assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
		});
		CKFinder.setupCKEditor( editor );
		editor.on("instanceReady", function(event)
		{
			_doc.scrollTop(_doc.height()-(window.innerHeight+215));
		});  
    var data = {
      editor:editor,title:'#_ckeditor_title_'+id,ckeditor:'_ckeditor_'+id
    }
    return data;
  }
  function savejournal (element) {
  	_page.find(element).prop('disabled',true);
    _page.find(".form-group").removeClass('has-error').find(".help-block").remove();
  	var form_data = new FormData();  
    var valid = true;
    var invalid_file = false;
    form_data.append("sub_count",_ckeditor_ids.length); 
    form_data.append('token', localStorage.getItem("token"));  
    form_data.append("reference",_page.find("select[name='reference']").val()); 
    if(edit){
      form_data.append("id",_journal_id); 
      form_data.append("edit",true); 
    }
    var title = _page.find("input[name='title']");
    var year = _page.find("input[name='year']");
    var stat_prop = _page.find("input[name='stat_prop']");
    var topic = _page.find("select[name='topic']");
    var file = _page.find("input[type='file']");
    var proponents = _page.find("select[name='proponents']");
    var tags = _page.find("input[name='tags']");
    if(title.val()==""){
    	errorMessage(title,title.attr('placeholder')+" is required");
	    valid = false;
    }else{
    	form_data.append("title",title.val()); 
    }
    if(year.val()==""){
    	errorMessage(year,year.attr('placeholder')+" is required");
	    valid = false;
    }else{
    	form_data.append("year",year.val()); 
    }
    if(stat_prop.val()==""){
    	errorMessage(stat_prop,stat_prop.attr('placeholder')+" is required");
	    valid = false;
    }else{
    	form_data.append("stat_prop",stat_prop.val()); 
    }
    if(topic.val()==""){
      topic.parent().addClass('has-error').append(("<p class='help-block'>"+topic.attr('placeholder')+" is required</p>"));
	    valid = false;
    }else if(topic.val()=="other"){
      var _other_topic = topic.next();
      if(_other_topic.val()==""){
        topic.parent().addClass('has-error').append(("<p class='help-block'>"+topic.attr('placeholder')+" is required</p>"));
        valid = false;
      }else{
        form_data.append("topic",_other_topic.val()); 
      }
    }else{
      form_data.append("topic",topic.val()); 
    }
    if(file.val()==""){
      if(!edit){
      file.next().after("<p class='help-block'>"+(file.attr('placeholder')+" is required")+"</p>").parent().addClass('has-error');
      valid = false;
      }
    }else{
    	var _file = file[0].files[0];
	    var fileType = _file["type"];
	    var ValidImageTypes = ["application/pdf","application/msword","application/vnd.openxmlformats-officedocument.wordprocessingml.document"];
	    if ($.inArray(fileType, ValidImageTypes) < 0) {
	        toastr['error']("Invalid file!"); 
	        valid = false;
	        invalid_file = true;
	    }else{
	    	form_data.append('file', _file);  
	    }                   
    }
    if(proponents.val()==""){
    	proponents.next().after("<p class='help-block'>"+(proponents.attr('placeholder')+" is required")+"</p>").parent().addClass('has-error');
	    valid = false;
    }else{
    	form_data.append('proponents', proponents.val());  
    }
    if(tags.val()==""){
    	errorMessage(tags,tags.attr('placeholder')+" is required");
	    valid = false;
    }else{
    	form_data.append('tags', tags.val());  
    }
    if(_ckeditor_ids.length>0){
    	_page.find("#documentation input").each( function(index, element) {
	      element = _page.find(element);
	      if(element.val().trim()==""){
	        errorMessage(element,element.attr('placeholder')+" is required");
	        valid = false;
	      }else{
	      	form_data.append(element.attr('name'), element.val()); 
	      }
	    });
    }
	if(valid){
		var empty_ckeditor = false;
	    for(var instanceName in CKEDITOR.instances) { 
	    	if(CKEDITOR.instances[instanceName].getData()==""){
	    		empty_ckeditor = true;
	    	}
		}
		if(empty_ckeditor){
			toastr.clear();
			toastr['error']("Fill up all Editors!");
      _page.find(element).prop('disabled',false);
		}else{
			form_data.append("abstract",CKEDITOR.instances['_document_abstract'].getData());
			form_data.append("content",CKEDITOR.instances['_document_full'].getData());
			for(var instanceName in CKEDITOR.instances) { 
		    	if(instanceName!="_document_abstract"&&instanceName!="_document_full"){
		    		form_data.append(instanceName,CKEDITOR.instances[instanceName].getData()); 
		    	}
			}
			$.ajax({
                url: data_url+"/_upload_file.php", // point to server-side PHP script 
                dataType: 'text',  // what to expect back from the PHP script, if anything
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(data){
                  _page.find(element).prop('disabled',false);
                    data = JSON.parse(data);
                    if(data.result=="success"){
                      toastr['success']("Successfully Saved");
                      location.hash='';
                      loadpage('user');
                    }else{
					  toastr['error'](data.result); 
                    }
                }
     		});
		}
	}else{
		if(!invalid_file){
			toastr['error']("Fill up all Fields!");
		}
    _page.find(element).prop('disabled',false);
	}
  	
  }

  topic(); 

  if(!!localStorage.getItem("page")&&localStorage.getItem("page")!="home"){
    loadpage(localStorage.getItem("page"));
  }else{
    loadpage(!!localStorage.getItem('id')?'home_in':'home');
  }

  _doc.on('hidden.bs.modal',"#details_modal", function () {
	  if(formodal){
	  		formodal=false;
	  		loadpage("file");
	  }
  })