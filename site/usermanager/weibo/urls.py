# -*- coding: utf-8 -*-
from django.conf.urls.defaults import *

urlpatterns = patterns('weibo.views',
    (r'^connect$', 'connect'),
    (r'^saveauth$', 'saveauth'),
                       )
