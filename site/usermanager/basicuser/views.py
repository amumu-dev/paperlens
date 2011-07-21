# Create your views here.
from django.http import HttpResponse
from django.shortcuts import render_to_response,redirect
from django.contrib.auth import authenticate, login, logout
from django.contrib.auth.models import User

from weibo.models import SinaLink
from doubancon.models import DoubanLink

hostname = "http://127.0.0.1:8000"
basicuserurl = hostname+"/basicuser"
loginurl = hostname+"/basicuser/login"
regurl = hostname+"/basicuser/reg"

class NotOne2OneLink(Exception):
    pass

#DJANGO itself has the view of login or register
#try them 

def connectstatus(request):
    """
    show wether user has connect sina or douban
    """
    if request.user.is_authenticated():
        # user login with paperlens id
        status = {}
        status['user'] = request.user
        
        slcount = SinaLink.objects.filter(theuser__exact=request.user).count()
        if slcount == 1:
            status['sinacon'] = '1'
            status['sinaid'] = SinaLink.objects.filter(theuser__exact=request.user)[0].sinaid
            status['sinainfo'] = "sinaid:"+str(status['sinaid'])
        elif slcount == 0:
            status['sinacon'] = '0'
        else:
            raise NotOne2OneLink("more than one sina id connect to the same paperlen user")

        dlcount = DoubanLink.objects.filter(theuser__exact=request.user).count()
        if dlcount == 1:
            status['doubancon'] = '1'
            status['doubanuid'] = DoubanLink.objects.filter(theuser__exact=request.user)[0].doubanuid
            status['doubanuinfo'] = "doubanid:"+str(status['doubanuid'])
        elif dlcount == 0:
            status['doubancon'] = '0'
        else:
            raise NotOne2OneLink("more than one douban id connect to the same paperlen user")
        return render_to_response("basicuser/connectstatus.html",status)

    else:
        #redirect to the register or login page
        return redirect("/basicuser/loginorregister.html")

def loginhandler(request):
    """
    the page
    GET method : show the login page
    POST method : finish the login process
    """
    try:
        #code from https://docs.djangoproject.com/en/dev/topics/auth/
        username = request.POST['username']
        password = request.POST['password']
        user = authenticate(username=username,password=password)
        if user is not None:
            if user.is_active:
                login(request,user)
                return redirect(basicuserurl)
        else:
            infodic = {}
            infodic['theurl'] = loginurl
            infodic['thedesp'] = "re input username and password"
            return render_to_response("tools/info.html",infodic)
    except:
        return render_to_response("basicuser/login.html")
        

def reghandler(request):
    """
    GET method : show the register page
    POST method : finish the register process
    """
    if request.method == 'POST':
        #code from https://docs.djangoproject.com/en/dev/topics/auth/
        username = request.POST['username']
        email = request.POST['email']
        password = request.POST['password']
        if User.objects.filter(username__exact=username).count() != 0:
            infodic = {}
            infodic['theurl'] = regurl
            infodic['thedesp'] = "use another username"
            return HttpResponse("use another username")
            #return render_to_response("tools/info.html",infodic)
        else:
            """
            user = User()
            user.username = username
            user.email = email
            user.set_password(password)
            user.save()
            #why here can not save to data base
            """
            user = User.objects.create_user(username, email, password)
            if user is not None:
                user.save()
                logout(request)
                user = User.objects.filter(username__exact=username)[0]
                user.backend='django.contrib.auth.backends.ModelBackend' 
                login(request,user)
                return HttpResponse("reg user"+username)
                #return redirect(hostname)
            else:
                infodic = {}
                infodic['theurl'] = regurl
                infodic['thedesp'] = "can not create a new user"
                return HttpResponse("can not create a new user")
                #return render_to_response("tools/info.html",infodic)
    else:
        return render_to_response("basicuser/reg.html")

def logouthandler(request):
    """
    simple log out
    redirect to the home page
    """
    logout(request)
    return redirect(basicuserurl)

def index(request):
    """
    not know for what...
    """
    dic = {}
    dic['user'] = request.user
    return render_to_response("basicuser/index.html",dic)
