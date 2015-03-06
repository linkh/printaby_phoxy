hook_url_click = function()
{
  $("body").on("click", "a", function()
  {
    var url = $(this).attr('href');
    
    if (url == undefined || $(this).is('[not-phoxy]'))
      return true;

    return url_hook(url, false);
  });

  window.onpopstate = function()
  {
    on_pop_state.apply(this, arguments);
    //analytics.page();
  }
}
hook_url_click();

url_hook = function (url, not_push)
{
  if (url.indexOf('#') != -1)
    return true;
  

  if (url[0] == '/')
    url = url.substring(1);
  
  phoxy.MenuCall(url, undefined, undefined, not_push);
  return false; 
}

on_pop_state = function(e)
{
  var path = e.target.location.pathname;
  var hash = e.target.location.hash;

  url_hook(path, true);
}

patch_phoxy = function()
{
  var origin_hook = EJS.Canvas.prototype.hook_first;
  EJS.Canvas.prototype.hook_first = function()
  {
    return $(origin_hook.apply(this, arguments));
  }
}
patch_phoxy();