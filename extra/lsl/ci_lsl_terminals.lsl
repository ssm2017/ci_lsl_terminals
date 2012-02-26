// @package ci_lsl_terminals
// @copyright Copyright wene / ssm2017 Binder / S.Massiaux (C) 2012. All rights reserved.
// @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL, see LICENSE.php
// slrenbox is free software and parts of it may contain or be derived from the
// GNU General Public License or other free or open source software licenses.

// **********************
//      USER PREFS
// **********************
// url ex: string url = "http://test.com";
string url = "http://127.0.0.1/ci_lsl_terminals";
// password : the password you put in the joomla component config
string password = "";
// the terminal name on the website
string website_terminal_name = "";
// *********************************
//      STRINGS
// *********************************
// symbols
string _SYMBOL_RIGHT = "✔";
string _SYMBOL_WRONG = "✖";
string _SYMBOL_WARNING = "⚠";
string _SYMBOL_RESTART = "⟲";
string _SYMBOL_HOR_BAR_1 = "⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌⚌";
string _SYMBOL_HOR_BAR_2 = "⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊⚊";
string _SYMBOL_ARROW = "⤷";
// common
string _RESET = "Reset";
// terminal
string _UPDATING_TERMINAL = "Updating terminal...";
string _TERMINAL_INSERTED = "Terminal inserted.";
string _TERMINAL_UPDATED = "Terminal updated.";
string _TERMINAL_SAVE_ERROR = "Error saving terminal";
// http errors
string _REQUEST_TIMED_OUT = "Request timed out";
string _FORBIDDEN_ACCESS = "Forbidden access";
string _PAGE_NOT_FOUND = "Page not found";
string _INTERNET_EXPLODED = "the internet exploded!!";
string _SERVER_ERROR = "Server error";
// ============================================================
//      NOTHING SHOULD BE MODIFIED UNDER THIS LINE
// ============================================================
// separators
string PARAM_SEPARATOR = "|";
// some vars
string terminal_name = "";
// messages integers
integer TERMINAL_INSERTED = 70051;
integer TERMINAL_UPDATED = 70052;
integer TERMINAL_SAVE_ERROR = 70053;
// build pos
string buildPos()
{
    vector pos = llGetPos();
    return (string)llFloor(pos.x)+"/"+(string)llFloor(pos.y)+"/"+(string)llFloor(pos.z);
}
string getParcelName()
{
    return llList2String(llGetParcelDetails(llGetPos(),[PARCEL_DETAILS_NAME]), 0);
}
// **********************
//          HTTP
// **********************
key updateTerminalId;
updateTerminal(string terminal_url)
{
    llOwnerSay(_SYMBOL_HOR_BAR_2);
    llOwnerSay(_SYMBOL_ARROW+ " "+ _UPDATING_TERMINAL);
    // get the website box name
    if (website_terminal_name == "")
    {
        website_terminal_name = terminal_name;
    }
    // building password
    integer keypass = (integer)llFrand(9999)+1;
    string md5pass = llMD5String(password, keypass);
    // sending values
    updateTerminalId =  llHTTPRequest( url+ "/inworld/update_terminal", [HTTP_METHOD, "POST", HTTP_MIMETYPE, "application/x-www-form-urlencoded"],
                    "uuid="+ llGetKey()
                    +"&url="+ llEscapeURL(terminal_url)
                    +"&name="+ website_terminal_name
                    +"&region="+ (string)llGetRegionName()
                    +"&parcel="+ getParcelName()
                    +"&position="+buildPos()
                    +"&password="+ md5pass
                    +"&key="+ (string)keypass
                    );
}
// get server answer
getServerAnswer(integer status, string body) {
    llOwnerSay(_SYMBOL_HOR_BAR_2);
    if (status == 499) {
        llOwnerSay(_SYMBOL_WARNING+ " "+ (string)status+ " "+ _REQUEST_TIMED_OUT);
    }
    else if (status == 403) {
        llOwnerSay(_SYMBOL_WARNING+ " "+ (string)status+ " "+ _FORBIDDEN_ACCESS);
    }
    else if (status == 404) {
        llOwnerSay(_SYMBOL_WARNING+ " "+ (string)status+ " "+ _PAGE_NOT_FOUND);
    }
    else if (status == 500) {
        llOwnerSay(_SYMBOL_WARNING+ " "+ (string)status+ " "+ _SERVER_ERROR);
    }
    else if (status != 403 && status != 404 && status != 500) {
        llOwnerSay(_SYMBOL_WARNING+ " "+ (string)status+ " "+ _INTERNET_EXPLODED);
        llOwnerSay(body);
    }
}
// ***********************
//  INIT PROGRAM
// ***********************
default
{
    on_rez(integer number)
    {
        llOwnerSay(_SYMBOL_HOR_BAR_2);
        llOwnerSay(_SYMBOL_RESTART+ " "+ _RESET);
        llResetScript();
    }

    changed(integer change)
    {
        if (change & CHANGED_REGION_START)
        {
            llResetScript();
        }
    }
    state_entry()
    {
        terminal_name = llGetObjectName();
        llRequestURL();
    }

    http_request(key id, string method, string body)
    {
        if (method == URL_REQUEST_GRANTED)
        {
            updateTerminal(body);
        }
        else if (method == "GET")
        {
            llHTTPResponse(id,200,"Online");
        }
    }
    http_response(key request_id, integer status, list metadata, string body)
    {
        if (request_id != updateTerminalId)
        {
            return;
        }
        if ( status != 200 )
        {
            getServerAnswer(status, body);
        }
        else
        {
            body = llStringTrim( body , STRING_TRIM);
            list data = llParseString2List(body, [PARAM_SEPARATOR],[]);
            string command = llList2String(data,0);
            llOwnerSay(_SYMBOL_HOR_BAR_2);
            if (command == "70051")
            {
              llOwnerSay(_SYMBOL_ARROW+ " "+ _TERMINAL_INSERTED);
            }
            else if (command == "70052")
            {
              llOwnerSay(_SYMBOL_ARROW+ " "+ _TERMINAL_UPDATED);
            }
            else if (command == "70053")
            {
              llOwnerSay(_SYMBOL_WARNING+ " "+ _TERMINAL_SAVE_ERROR+ " : "+ llList2String(data,1));
            }
            else
            {
                llOwnerSay(body);
            }
        }
    }
}
