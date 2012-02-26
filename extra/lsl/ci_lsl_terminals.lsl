key http_request_id;
string url = "http://127.0.0.1/ci_lsl_terminals/inworld/update_terminal";
default
{
    state_entry()
    {
        llRequestURL();
    }

    http_request(key id, string method, string body)
    {
        if (method == URL_REQUEST_GRANTED)
        {
            http_request_id =  llHTTPRequest( url, [HTTP_METHOD, "POST", HTTP_MIMETYPE, "application/x-www-form-urlencoded"],
                    "uuid="+ llGetKey()+"&url="+ llEscapeURL(body));
        }
        else if (method == "GET")
        {
            llHTTPResponse(id,200,"Online");
        }
    }
    http_response(key request_id, integer status, list metadata, string body)
    {
        if (request_id == http_request_id)
        {
            llOwnerSay(body);
        }
    }
}
