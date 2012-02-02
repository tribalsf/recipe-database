
var admin = {

  init: function() {

    // firefox not obeying 'selected'
    if (document.forms[0]) {
      document.forms[0].reset(); 
    }

    // global listing handler, show _hover and display the hidden delete button
    $('.listing tr').hover(function() {

      if ($(this).children().is('TD')) {
        $(this).addClass('listing_hover');
        $(this).find('.delete_button').transition({opacity: 1}, 0);
      }

    }, function() {
      $(this).removeClass('listing_hover');
      $(this).find('.delete_button').transition({opacity: 0}, 0);
    });


    $('.delete_button').click(function(e) {
      e.stopPropagation();
      $(this).closest('TR').toggleClass('listing_active');

      $('.listing_active').length > 0 ?  $('.button_delete_selected').removeClass('button_disabled') : 1;
      $('.listing_active').length == 0 ?  $('.button_delete_selected').addClass('button_disabled') : 1;

    });

    $('.site_buttons div').hover(function() { $(this).toggleClass('site_button_hover'); });
    $('.site_buttons div').click(function() { 

      switch ($(this).data('value')) {

        case 'grilling':
        case 'hiddenvalley':

            if (admin.gets().recipe) {
              location.href = g.G_URL + '?recipe=' + admin.gets().recipe + '&site=' + $(this).data('value');
            } else {
              location.href = g.G_URL + '?site=' + $(this).data('value');
            }

          break;
        case 'details' :
          location.href = g.G_URL + '?details';
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

        case 'add_detail_recipe' :
          modify.add('detail');
          break;

        case 'delete_selected' :
          admin.delete_selected();
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

  delete_selected: function() {

    var datas = [];
    $('.listing_active').each(function(key, value) {
      datas.push({
        type: $(this).find('.delete_button').data('type'),
        value: $(this).find('.delete_button').data('value')
      });
    });

    console.log(datas);


  },

  notify: function(message) {

    var n = $('.notify');
    n.html(message);

    admin.center(n, {noTop: true});
    n.transition({top: 0});

    setTimeout(function() { 
      n.transition({top: '-40px'});
    }, 3000);

  },

  // center an absolute div
  center: function(e, params) {    

  var middle = ($(window).width() / 2) - (e.outerWidth() / 2);
  var top = ($(window).scrollTop() + 100*1);

  if (params && params.noTop) {
    $(e).css({left: middle + 'px'});
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
        $(this).transition({scale: 1.2});
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

  add: function() {

    var details = {
      site: $('#site').val(),
      type: $('#type').val(),
      value: $('#value').val()
    };

    $.get(g.G_URL + 'ajx/details.php', {action: 'add', data: JSON.stringify(details)}, function(response) {

      if (response.error != false) {
        admin.notify(response.error);
      } else {
        admin.notify('Detail added successfully');
        setTimeout(function() { location.href = g.G_URL + '?details'; });
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

    $('.detail_type').change(modify.detailtype);

    $('#ingredient, #instruction').keyup(function(e) {

      if (e.keyCode == 13) {
        var val = $(this).val();
        var cmd = $(this).data('cmd');
        if (val && val != '' && cmd) {
          eval(cmd);
        }
      }

    });

  },

  detailtype: function() {
    var type = $('.detail_type').val().replace(/ /g, '_');
    $('.detail_value').hide();
    $('.detail_value_' + g.site + '_' + type).fadeIn();
    $('.detail_value').val();
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
        var valueid = ('.detail_value_' + g.site + '_' + $('#detail_type').val().replace(/ /g, '_'));
        var data = { recipe_id: modify.recipe_id, type: $('#detail_type').val(), value: $(valueid).val() }
        var fields = ['#detail_value'];
        break;

    }

    $.get('/ajx/add.php', {type: type, data: JSON.stringify(data)}, function(response) {

      if (response.error != false) {
        admin.notify(response.error);
      } else {

        admin.notify(type + ' added successfully');

        for (var i = 0, l = fields.length; i != l; i++) {
          $(fields[i]).val('');
        }

        if (type == 'instruction') {
          $('#step').val(data.step*1+1);
        }

        $('.' + type + '_listing').html(response.html);
        $('.newrow').transition({backgroundColor: '#fff'}, '1000', 'in');
      }

    }, 'json');

  }

}

