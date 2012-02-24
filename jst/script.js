
var admin = {

  init: function() {

    admin.handlers();

    // firefox not obeying 'selected'
    if (document.forms[0]) {
      document.forms[0].reset(); 
    }

    $('.site_buttons div').hover(function() { $(this).toggleClass('site_button_hover'); });
    $('.site_buttons div').click(function() { 

      switch ($(this).data('value')) {

        case 'both':
        case 'grilling':
        case 'hiddenvalley':

          $(this).parent('.site_buttons').children('.site_button').removeClass('site_button_active');
          //$('.site_button').removeClass('site_button_active');

          $(this).addClass('site_button_active');

          if ($(this).data('detail')) {
            details.update($(this));
          }

          break;

        case 'details' :
          location.href = g.G_URL + '?details';
          break;

        case 'deleted' :
          location.href = g.G_URL + '?deleted';
          break;

        case 'add_recipe' :
          location.href = g.G_URL + '?recipe=add';
          break;
      }
      
    });

    $('.button').hover(function() { $(this).toggleClass('button_hover'); });

    $('.button').click(function() { 

      $(this).addClass('button_active'); 

      var that = this;

      setTimeout(function() { 
        $(that).removeClass('button_active'); 
        $(that).removeClass('button_hover'); 
      }, 500);

      switch ($(this).data('action')) {

        case 'add_detail' :
          details.add();
          break;

        case 'add_ingredient' :
          modify.add('ingredient');
          break;

        case 'add_instruction' :
          modify.add('instruction');
          break;

        case 'create_set' :
          modify.set();
          break;

        case 'add_recipeDetail' :
          modify.add('detail');
          break;

        case 'delete_selected' :
          admin.delete_selected();
          break;


        case 'save_recipe' :
          modify.save();
          break;

        case 'back' :
          location.href = g.G_URL;
          break;

        default: 
          console.log($(this).data('action'));
          break;
      }

    });

  },

  handlers: function() {

    // global listing handler, show _hover and display the hidden delete button
    $('.listing tr').unbind('hover');
    $('.listing tr').hover(function() {

      if ($(this).children().is('TD')) {
        $(this).addClass('listing_hover');
        $(this).find('.delete_button').transition({opacity: 1}, 0);
      }

    }, function() {
      $(this).removeClass('listing_hover');
      $(this).find('.delete_button').transition({opacity: 0}, 0);
    });

    $('.delete_button').unbind('click');
    $('.delete_button').click(function(e) {
      e.stopPropagation();
      $(this).closest('TR').toggleClass('listing_active');

      $('.listing_active').length > 0 ?  $('.button_delete_selected').removeClass('button_disabled') : 1;
      $('.listing_active').length == 0 ?  $('.button_delete_selected').addClass('button_disabled') : 1;

    });

  },

  delete_selected: function() {

    var datas = [];
    $('.listing_active').each(function(key, value) {
      datas.push({
        type: $(this).find('.delete_button').data('type'),
        value: $(this).find('.delete_button').data('value')
      });
    });

    if (datas.length == 0) {
      admin.status('You must select items to delete first', {type: 'error'});
      return true;
    }
    admin.notify('Deleting selected..');
    $.get('ajx/delete.php', {datas: JSON.stringify(datas)}, function(response) {

      if (!response.error) {

        $('.listing_active').fadeOut();
        admin.status('Deleted');

      }

    }, 'json');


  },

  notify: function(message, params) {

    var p = {fadeOut: 3000, close: false};

    if (params) {
      for (var param in params) {
        p[param] = params[param];
      }
    }

    var n = $('.notify');

    if (p.close || message == false) { 
      n.transition({opacity: 0}, 20, 'out'); 
      return true;
    }

    n.html(message);

    admin.center(n);
    n.transition({opacity: 1}, 20, 'in');

    if (p.fadeOut) { 
      setTimeout(function() { n.transition({opacity: 0}, 20, 'out'); }, p.fadeOut);
    }

  },

  status: function(message, params) {

    var p = {fadeOut: 3000, type: 'check', close: false};

    if (params) {
      for (var param in params) {
        p[param] = params[param];
      }
    }

    var s = $('.status');
    var si = $('.status div');

    s.removeClass('status_check').removeClass('status_error');
    si.removeClass('sprite_check').removeClass('sprite_error');

    s.addClass('status_' + p.type);
    si.addClass('sprite_' + p.type);

    if (p.close) { 
      s.transition({opacity: 0}); 
    }

    $('.status_body').html(message);

    if (s.css('opacity') == 0) {
      admin.center(s, {top: 50});
      s.transition({opacity: 1}, 500, 'in');
    }

    if (p.fadeOut) { 
      setTimeout(function() { s.transition({opacity: 0}, 500, 'out'); }, p.fadeOut);
    }

  },

  // center an absolute div
  center: function(e, params) {    

    var middle = ($(window).width() / 2) - (e.outerWidth() / 2);
    //var top = ($(window).scrollTop()*1);
    var top = 0;

    if (params && params.top) {
      $(e).css({top: (top +params.top*1) + 'px', left: middle + 'px'});
    } else {
      $(e).css({top: top + 'px', left: middle + 'px'});
    }

    return true;

  },

  gets: function() {

    qs = document.location.search.split("+").join(" ");
    var params = {}, tokens, re = /[?&]?([^=]+)=([^&]*)/g;

    while (tokens = re.exec(qs)) {
      params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
    }

    return params;

  }

}

var listing = {

  init: function() {

    $('.listing tr').click(function() {

      if ($('.listing_active').length > 0) {
        $('TR').removeClass('listing_active');
        return true;
      }

      if ($(this).children().is('TD') && $(this).data('recipe') != undefined) {
        $('.listing tr').removeClass('listing_selected');
        $(this).addClass('listing_selected');
        //$(this).transition({scale: 1.2}, function() { $(this).transition({scale: 1.0}, 100); });
        admin.notify('Loading Recipe..');
        location.href = g.G_URL + '?recipe=' + $(this).data('recipe');
      }

    });

    $('.page').hover(function() { $(this).toggleClass('page_hover'); });
    $('.page').click(listing.page);

  },

  page: function() {
    $(this).addClass('page_active');
    $(this).transition({scale: 2});
    location.href = g.G_URL + '?page=' + $(this).html();
  }

}

var details = {

  init: function() {

    $('.formmason').masonry({
      itemSelector: '.formsmall',
      isFitWidth: true,
      columnWidth: 420
    });

    $('#value').keyup(function(e) {

      if (e.keyCode == 13) {
        var val = $(this).val();
        var cmd = $(this).data('cmd');
        if (val && val != '' && cmd) {
          eval(cmd);
        }
      }

    });

  },

  add: function() {

    var details = {
      name: $('#name').val(),
      type: $('#type').val(),
      value: $('#value').val(),
      site: $('#site').val()
    };

    $.get(g.G_URL + 'ajx/details.php', {action: 'add', data: JSON.stringify(details)}, function(response) {

      if (response.error != false) {
        admin.status(response.error, {type: 'error'});
      } else {
        admin.status('Detail added successfully');
        setTimeout(function() { location.href = g.G_URL + '?details'; });
      }

    }, 'json');

  },

  update: function(obj) {

    var details = obj.data('data');
    details.site = obj.data('value');

    $.get(g.G_URL + 'ajx/details.php', {action: 'update', data: JSON.stringify(details)}, function(response) {
      if (response.error != false) {
        admin.status(response.error, {type: 'error'});
      } else {
        admin.status('Detail updated');
      }
    }, 'json');

  }

}

var g = {
  G_URL: false,
  site: false
}

var modify = {

  recipe_id: false,

  init: function() {

    modify.detailtype();

    $('.detail_name').change(modify.detailtype);

    $('#ingredient, #instruction, #set').keyup(function(e) {

      if (e.keyCode == 13) {
        var val = $(this).val();
        var cmd = $(this).data('cmd');
        if (val && val != '' && cmd) {
          eval(cmd);
        }
      }

    });

    $('#image').change(function(e) {

      if (!modify.recipe_id || modify.recipe_id == 'add') {
        admin.status('You must save your main recipe settings first');
        return true;
      }

      var file = e.target.files[0];

      if (file.type == '') {
        admin.status('Unknown file type');
        return true;
      }

      if (!file.type.match('image.*')) {
        admin.status('Your file must be a proper image');
        return true;
      }

      var imagetype =  file.type.substr(file.type.indexOf('/')+1);
      imagetype = (imagetype == 'jpeg') ? 'jpg' : imagetype;
      console.log(file.size);

      var reader = new FileReader();

      reader.onload = (function(theFile) {

        return function(e) {

          admin.notify('Uploading Image.. 0%', {fadeOut: false});

          var xhr = new XMLHttpRequest();
          xhr.open('POST', '/ajx/image.php?recipe_id=' + modify.recipe_id + '&imagetype=' + imagetype, true);
          xhr.setRequestHeader('Content-Type', 'multipart/form-data');
          xhr.setRequestHeader('X-File-Name', file.name);
          xhr.setRequestHeader('X-File-Size', file.size);
          xhr.setRequestHeader('X-File-Type', file.type); //add additional header

          xhr.upload.onprogress = function(e) { 
            var progress = Math.round((e.loaded / e.total) * 100) + '%'; 
            $('.notify').html('Uploading Image.. ' + progress);
          };

          xhr.upload.onloadend = function(response) { 
            admin.notify(false);
            admin.status('Upload Complete');
            setTimeout(function() { 
              location.href = g.G_URL + '?recipe=' + modify.recipe_id;
            }, 3000);
          };

          xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
              if (xhr.status == 200) {
                console.log(JSON.parse(xhr.response));
              }
            }
          };

          xhr.send(e.target.result);

        };

      })(file);

      reader.readAsBinaryString(file);
      
    });

  },

  detailtype: function() {
    var type = $('.detail_name').val().replace(/ /g, '_');
    $('.detail_value').hide();
    $('.detail_value_' + type).fadeIn();
    //$('.detail_value').val();
  },

  save: function() {

    var datas = {
      recipe_id: modify.recipe_id,
      title: $('#title').val(),
      site: $('.site_buttons_site .site_button_active').data('value'),
      servings: $('#servings').val(),
      prep_time: $('#prep_time').val(),
      cook_time: $('#cook_time').val()
    };

    if (datas.title == '') {
      admin.status('You must specify a title to save', {type: 'error'});
      return true;
    }

    admin.notify('Saving..');

    $.get('/ajx/save.php', {data: JSON.stringify(datas)}, function(response) {

      admin.notify(false, {close: true});
      if (response.error) {
        admin.status(response.message, {'type': error});
      } else {
        admin.status(response.message);
        modify.recipe_id = response.recipe_id;
        //setTimeout(function() { location.href = g.G_URL + '?recipe=' + response.recipe_id; }, 1000);
      }

    }, 'json');

  },

  add: function(type) {

    switch (type) {

      case 'ingredient':
        var data =  { recipe_id: modify.recipe_id, title: $('#ingredient').val() }
        var fields = ['#ingredient'];
        break;

      case 'instruction':
        var data =  { recipe_id: modify.recipe_id, step: $('#step').val(), title: $('#instruction').val() }
        var fields = ['#instruction'];
        break;

      case 'detail':
        var typeid = ('.detail_type_' + $('#detail_name').val().replace(/ /g, '_'));
        var valueid = ('.detail_value_' + $('#detail_name').val().replace(/ /g, '_'));
        var data = { recipe_id: modify.recipe_id, name: $('#detail_name').val(), type: $(typeid).val(), value: $(valueid).val() }
        var fields = ['#detail_value'];
        break;

    }

    if (modify.recipe_id == 'add') {
      admin.status('You must first save your recipe', {type: 'error'});
      return true;
    }

    admin.notify('adding..', {fadeOut: false});
    $.get('/ajx/add.php', {type: type, data: JSON.stringify(data)}, function(response) {

      admin.notify(false, {close: true});
      if (response.error != false) {
        admin.status(response.error, {type: 'error'});
      } else {

        admin.status(type + ' added successfully');

        for (var i = 0, l = fields.length; i != l; i++) {
          $(fields[i]).val('');
        }

        if (type == 'instruction') {
          $('#step').val(data.step*1+1);
        }

        $('.' + type + '_listing').html(response.html);
        $('.newrow').transition({backgroundColor: '#fff'}, '1000', 'in', function() {

          $('.newrow').attr('style', '');
          $(document).find('.newrow').removeClass('newrow');
          
        });

        admin.handlers();
      }

    }, 'json');

  },

  set: function() {

    var set = $('#set').val();

    console.log(set);

    if (set == '') {
      admin.status('You must specify a set name', {type: 'error'});
      return true;
    }

    admin.notify('Creating set..');
    var data = {recipe_id: modify.recipe_id, set: set};

    $.get('/ajx/set.php', { data: JSON.stringify(data) }, function(response) {

      admin.notify(false, {close: true});
      admin.status('Set Created');
      setTimeout(function() {
        location.href = g.G_URL + '?recipe=' + modify.recipe_id;
      }, 1000);

    }, 'json');

  }

}
