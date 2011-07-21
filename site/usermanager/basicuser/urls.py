# -*- coding: utf-8 -*-
from django.conf.urls.defaults import *

urlpatterns = patterns('basicuser.views',
    (r'^$', 'index'),
    (r'^login$', 'loginhandler'),
    (r'^logout$', 'logouthandler'),                       
    (r'^reg$', 'reghandler'),                       
    (r'^connectstatus$', 'connectstatus'),
    )
