#from weibo.models import Sina_Request_Token,SinaLink
from doubancon.models import Douban_Request_Token,DoubanLink
from django.contrib import admin

admin.site.register(Douban_Request_Token)
admin.site.register(DoubanLink)
