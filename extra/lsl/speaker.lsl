// @package ci_lsl_terminals
// @copyright Copyright wene / ssm2017 Binder / S.Massiaux (C) 2012. All rights reserved.
// @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL, see LICENSE.php
// slrenbox is free software and parts of it may contain or be derived from the
// GNU General Public License or other free or open source software licenses.

// **********************
//      CONSTANTS
// **********************
// http_request
integer RESET = 20000;
integer HTTP_REQUEST_GET = 70061;
integer HTTP_REQUEST_POST = 70062;
integer HTTP_REQUEST_RESPONSE = 70063;
// params
integer SET_PARAMS = 20025;
integer GET_PARAMS = 20026;
string FIELD_SEPARATOR = "Ⅱ"; // U+2161 ROMAN NUMERAL TWO
string VALUE_SEPARATOR = "Ⅰ"; // U+2160 ROMAN NUMERAL ONE
// **********************
//        HELPERS
// **********************
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
    link_message(integer sender_num, integer num, string str, key id) {
        if (num == RESET) {
            llResetScript();
        }
        else if (num == HTTP_REQUEST_GET) {
            list values = llParseStringKeepNulls(str,[VALUE_SEPARATOR],[]);
            if (llList2String(values, 0) == "/speaker") {
                list fields = parseQueryString(llList2String(values, 1));
                if (llList2String(fields, 0) == "sentence") {
                    llSay(0, llList2String(fields, 1));
                    llMessageLinked(LINK_THIS, HTTP_REQUEST_RESPONSE, "Said : "+ llList2String(fields, 1), id);
                }
            }
        }
    }
}
