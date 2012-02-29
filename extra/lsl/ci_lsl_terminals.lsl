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
string password = "0000";
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
string _TERMINAL_INIT = "Terminal initialisation";
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
// some vars
string terminal_name = "";
// **********************
//      CONSTANTS
// **********************
// http_request
integer HTTP_REQUEST_GET = 70061;
integer HTTP_REQUEST_POST = 70062;
integer HTTP_REQUEST_RESPONSE = 70063;
// params
integer SET_PARAMS = 20025;
integer GET_PARAMS = 20026;
string FIELD_SEPARATOR = "Ⅱ"; // U+2161 ROMAN NUMERAL TWO
string VALUE_SEPARATOR = "Ⅰ"; // U+2160 ROMAN NUMERAL ONE
// messages integers
integer TERMINAL_INSERTED = 70051;
integer TERMINAL_UPDATED = 70052;
integer TERMINAL_SAVE_ERROR = 70053;
integer RESET = 20000;
// **********************
//        HELPERS
// **********************
// get the parcel name
string getParcelName() {
    return llList2String(llGetParcelDetails(llGetPos(),[PARCEL_DETAILS_NAME]), 0);
}
// give params to other scripts
giveParams() {
    // url Ⅱ password
    string params = url+FIELD_SEPARATOR
                    +password;
    llMessageLinked(LINK_THIS, SET_PARAMS, params, NULL_KEY);
}
// **********************
//          HTTP
// **********************
key updateTerminalId;
updateTerminal(string terminal_url) {
    llOwnerSay(_SYMBOL_HOR_BAR_2);
    llOwnerSay(_SYMBOL_ARROW+ " "+ _UPDATING_TERMINAL);
    // get the website box name
    if (website_terminal_name == "") {
        website_terminal_name = terminal_name;
    }
    // building password
    integer keypass = (integer)llFrand(9999)+1;
    string md5pass = llMD5String(password, keypass);
    // sending values
    updateTerminalId =  llHTTPRequest( url+ "/inworld/update_terminal", [HTTP_METHOD, "POST", HTTP_MIMETYPE, "application/x-www-form-urlencoded"],
                    "url="+ llEscapeURL(terminal_url)
                    +"&name="+ website_terminal_name
                    +"&parcel="+ getParcelName()
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
// extracts fields and values from a http request
list parseQueryString(string message) {
    list postData = [];         // The list with the data that was passed in.
    list parsedMessage = llParseString2List(message,["&"],[]);    // The key/value pairs parsed into one list.
    integer len = ~llGetListLength(parsedMessage);

    while(++len) {
        string currentField = llList2String(parsedMessage, len); // Current key/value pair as a string.

        integer split = llSubStringIndex(currentField,"=");     // Find the "=" sign
        if(split == -1) { // There is only one field in this part of the message.
            postData += [llUnescapeURL(currentField),""];
        } else {
            postData += [llUnescapeURL(llDeleteSubString(currentField,split,-1)), llUnescapeURL(llDeleteSubString(currentField,0,split))];
        }
    }
    // Return the strided list.
    return postData ;
}
// ***********************
//      MAIN PROGRAM
// ***********************
default {
    on_rez(integer number) {
        llOwnerSay(_SYMBOL_HOR_BAR_2);
        llOwnerSay(_SYMBOL_RESTART+ " "+ _RESET);
        llMessageLinked(LINK_THIS, RESET, "", NULL_KEY);
        llResetScript();
    }

    changed(integer change) {
        if (change & (CHANGED_REGION | CHANGED_REGION_START | CHANGED_TELEPORT | CHANGED_INVENTORY) ) {
            llMessageLinked(LINK_THIS, RESET, "", NULL_KEY);
            llResetScript();
        }
    }

    state_entry() {
        llOwnerSay(_TERMINAL_INIT);
        terminal_name = llGetObjectName();
        llRequestURL();
    }

    link_message(integer sender_num, integer num, string str, key id) {
        if (num == RESET) {
            llResetScript();
        }
        else if (num == GET_PARAMS) {
            giveParams();
        }
        else if (num == HTTP_REQUEST_RESPONSE) {
            llHTTPResponse(id,200,str);
        }
    }

    http_request(key id, string method, string body) {
        if (method == URL_REQUEST_GRANTED) {
            llOwnerSay(body);
            updateTerminal(body);
        }
        else if (method == "GET") {
            string x_query_string = llGetHTTPHeader(id, "x-query-string");
            string x_query_path = llGetHTTPHeader(id, "x-path-info");
            if (x_query_path == "/" && x_query_string == "command=get_status") {
                llHTTPResponse(id,200,"Online");
            }
            else {
                llMessageLinked(LINK_THIS, HTTP_REQUEST_GET, x_query_path+ VALUE_SEPARATOR+ x_query_string, id);
            }
        }
    }
    http_response(key request_id, integer status, list metadata, string body) {
        if (request_id != updateTerminalId) {
            return;
        }
        if ( status != 200 ) {
            getServerAnswer(status, body);
        }
        else {
            body = llStringTrim( body , STRING_TRIM);
            if (body == "70051") {
                llOwnerSay(_SYMBOL_ARROW+ " "+ _TERMINAL_INSERTED);
            }
            else if (body == "70052") {
                llOwnerSay(_SYMBOL_ARROW+ " "+ _TERMINAL_UPDATED);
            }
            else {
                list values = llParseStringKeepNulls(body,[VALUE_SEPARATOR],[]);
                if (llList2String(values, 0) == "70053") {
                    llOwnerSay(_SYMBOL_WARNING+ " "+ _TERMINAL_SAVE_ERROR+ " : "+ llList2String(values,1));
                }
                else {
                    llOwnerSay(body);
                }
            }
        }
    }
}
