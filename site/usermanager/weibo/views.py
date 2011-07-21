# Create your views here.
from django.http import HttpResponse
from django.shortcuts import render_to_response,redirect
#from django.
from weibopy.auth import OAuthHandler
from weibopy.api import API
from weibo.models import Sina_Request_Token,SinaLink

consukey = "1330786948"
consusec = "94fa907c07c9d6915927f48149f431d8"

hostname = "http://127.0.0.1:8000"
loginurl = hostname+"/basicuser/login"
connecstatusurl = hostname+"/basicuser/connectstatus"
confirmurl = hostname+"/weibo/saveauth"

class NotOne2OneLink(Exception):
    pass

def connect(request):
    """
    if not login with our account,must login to connect
    """
    if not request.user.is_authenticated():
        infodic = {}
        infodic['theurl'] = loginurl
        infodic['thedesp'] = "not login"
        #return redirect(loginurl)
        return render_to_response("tools/info.html",infodic)

    oah = OAuthHandler(consukey,consusec,confirmurl)
    
    theurl = oah.get_authorization_url()
    
    rt = Sina_Request_Token()
    rt.thekey = oah.request_token.key
    rt.secret = oah.request_token.secret
    rt.save()

    return redirect(theurl)

def saveauth(request):
    """
    need to add the function of setting cookie with render_to_response
    """
    
    if not request.user.is_authenticated():
        infodic = {}
        infodic['theurl'] = loginurl
        infodic['thedesp'] = "not login"
        #return redirect(loginurl)
        return render_to_response("tools/info.html",infodic)

    oah = OAuthHandler(consukey,consusec)
    ot = request.GET['oauth_token']
    #os = Sina_Request_Token.objects.filter("thekey = ",ot)[0].secret
    os = Sina_Request_Token.objects.filter(thekey__exact=ot)[0].secret
    ov = request.GET['oauth_verifier']
    
    oah.set_request_token(ot,os)
    access_token = oah.get_access_token(ov)
    
    #store the ackey and acsec
    #what is auth handler...
    apioah = OAuthHandler(consukey,consusec)
    apioah.setToken(access_token.key,access_token.secret)
    sapi = API(apioah)
    #sapi = API(access_token)
    sinauserinfo = sapi.me()
    #return HttpResponse(str(sinauserinfo.id))
    sid = sinauserinfo.id
    
    if SinaLink.objects.filter(theuser__exact=request.user).filter(sinaid__exact=sid).count() == 1:
        #may be authorized alread,update the ac token
        sl = SinaLink.objects.filter(theuser__exact=request.user).filter(sinaid__exact=sid)[0]
        sl.ackey = access_token.key
        sl.acsecret = access_token.secret
        sl.save()
    elif SinaLink.objects.filter(theuser__exact=request.user).filter(sinaid__exact=sid).count() == 0:
        #check one2one restriction
        if SinaLink.objects.filter(theuser__exact=request.user).count() != 0:
            raise NotOne2OneLink("user has already linked")
        if SinaLink.objects.filter(sinaid__exact=sid).count() != 0:
            raise NotOne2OneLink("sinaid has already linked")
        sl = SinaLink()
        sl.theuser = request.user
        sl.sinaid = sid
        sl.ackey = access_token.key
        sl.acsecret = access_token.secret
        sl.save()
    else:
        raise NotOne2OneLink("user and sinaid has more than one link")
    

    #try get
    """
    at = Access_Token()
    at.thekey = access_token.key
    at.secret = access_token.secret
    at.save()
    """
    #if have a user, then link the user
    #else must create a link

    infodic = {}
    infodic['theurl'] = connecstatusurl
    infodic['thedesp'] = "link sina weibo account success."
    #return render_to_response("tools/info.html",infodic)
    return redirect(connecstatusurl)

    #hr = HttpResponse(oah.get_username())
    #hr.set_cookie("sinatoken",ot,864000)
    #hr.set_cookie("sinaverify",ov,864000)
    #hr.set_cookie("sinaaccesstoken",access_token.key,864000)
    #how to set the cookie while using render_to_response?
    #return hr
