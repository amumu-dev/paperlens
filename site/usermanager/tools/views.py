from django.http import HttpResponse
from django.shortcuts import render_to_response,redirect

def info(request):
    """
    """
    return HttpResponse("hola")

def testredirect(request):
    """
    test the redirect
    """
    return redirect("http://www.baidu.com")
