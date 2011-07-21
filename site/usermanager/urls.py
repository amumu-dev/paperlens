from django.conf.urls.defaults import *

# Uncomment the next two lines to enable the admin:
from django.contrib import admin
admin.autodiscover()

urlpatterns = patterns('',
    # Example:
    # (r'^usermanager/', include('usermanager.foo.urls')),

    # Uncomment the admin/doc line below to enable admin documentation:
    # (r'^admin/doc/', include('django.contrib.admindocs.urls')),

    # Uncomment the next line to enable the admin:
    (r'^admin/', include(admin.site.urls)),
    (r'^basicuser/',include('basicuser.urls')),
    (r'^doubancon/',include('doubancon.urls')),
    (r'^weibo/',include('weibo.urls')),
    (r'^tools/',include('tools.urls')),
)
