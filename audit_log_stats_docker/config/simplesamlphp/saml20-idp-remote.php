<?php
/**
 * SAML 2.0 remote IdP metadata for SimpleSAMLphp.
 *
 * Remember to remove the IdPs you don't use from this file.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-remote
 */

$metadata['https://login.terena.org/wayf/saml2/idp/metadata.php'] = array (
  'entityid' => 'https://login.terena.org/wayf/saml2/idp/metadata.php',
  'contacts' => 
  array (
    0 => 
    array (
      'contactType' => 'technical',
      'givenName' => 'GÉANT AAI team',
      'emailAddress' => 
      array (
        0 => 'mailto:aai@geant.org',
      ),
    ),
  ),
  'metadata-set' => 'saml20-idp-remote',
  'SingleSignOnService' => 
  array (
    0 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
      'Location' => 'https://login.terena.org/wayf/saml2/idp/SSOService.php',
    ),
  ),
  'SingleLogoutService' => 
  array (
    0 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
      'Location' => 'https://login.terena.org/wayf/saml2/idp/SingleLogoutService.php',
    ),
  ),
  'ArtifactResolutionService' => 
  array (
  ),
  'NameIDFormats' => 
  array (
    0 => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
  ),
  'keys' => 
  array (
    0 => 
    array (
      'encryption' => false,
      'signing' => true,
      'type' => 'X509Certificate',
      'X509Certificate' => 'MIIEkjCCA3qgAwIBAgIJAL90CxMEVb/kMA0GCSqGSIb3DQEBBQUAMIGMMQswCQYDVQQGEwJOTDELMAkGA1UECBMCTkgxEjAQBgNVBAcTCUFtc3RlcmRhbTEPMA0GA1UEChMGVEVSRU5BMQwwCgYDVQQLEwNJVFMxHjAcBgNVBAMTFWh0dHBzOi8vdGVyZW5hLm9yZy9zcDEdMBsGCSqGSIb3DQEJARYOYWFpQHRlcmVuYS5vcmcwHhcNMTEwMTEyMTUyNjM4WhcNMjEwMTExMTUyNjM4WjCBjDELMAkGA1UEBhMCTkwxCzAJBgNVBAgTAk5IMRIwEAYDVQQHEwlBbXN0ZXJkYW0xDzANBgNVBAoTBlRFUkVOQTEMMAoGA1UECxMDSVRTMR4wHAYDVQQDExVodHRwczovL3RlcmVuYS5vcmcvc3AxHTAbBgkqhkiG9w0BCQEWDmFhaUB0ZXJlbmEub3JnMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwTxx8JBWSpBJiZgdvGOJDXLwaE29Opx1CBbIrYHm47Oy4btsf0BzCmfdSPDlydDm6//355hsQU8BgIh/waEwFZZCg/XyzrJEXCDTZBm1H210aT7FNp356azqKOO1bYWcku0xpFOWWf3jCIkjtOiTkbl12Tw7Y+zJRhV2+jleC5td3JxZ6k1qotgN+1cGwZ2Tv2HhSNeMC4QsGOyBqeP+7B1CLFqFZSiLWGVqcZi0fGkXf+SrTSEH/kLzdciEg2EePyQPcLCKNz9RiIhSmsLE/Rr1ksOvZGmyWFe7YsPyJOLsNyYcZTufDVwpl9fDuJdYy2GdMT1kSNNOpZXZ7QcgYwIDAQABo4H0MIHxMB0GA1UdDgQWBBQ6tVqjpKC8+30XF/qWlaZ3fUKTvDCBwQYDVR0jBIG5MIG2gBQ6tVqjpKC8+30XF/qWlaZ3fUKTvKGBkqSBjzCBjDELMAkGA1UEBhMCTkwxCzAJBgNVBAgTAk5IMRIwEAYDVQQHEwlBbXN0ZXJkYW0xDzANBgNVBAoTBlRFUkVOQTEMMAoGA1UECxMDSVRTMR4wHAYDVQQDExVodHRwczovL3RlcmVuYS5vcmcvc3AxHTAbBgkqhkiG9w0BCQEWDmFhaUB0ZXJlbmEub3JnggkAv3QLEwRVv+QwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQUFAAOCAQEAn+06i7zZE7MjuB68gCaNvnCkrgfumi4PWiP6kaE6+LU2MTbxdFyoSAoKh6Ft9TDi+8ANAsn5jRQ5xLUE4YoVbub/KufMwdlX0zO9i+Q//npDTFESnWsiMi7DHg/av1LtzrYYZvE2E1e5c/7wo/axx8Bk7qsE9YXFRs372vDkDwOGSkLbRtgwdCUX47CE/fXvccPDHH217XMed2cVOGFjQgidsFZlJbSfSvQjWYw5LIE0wo9RtsEu5I3WAIar8Wr6/nhVOgIBUStpcw94GwlPxLywfij5CJ9HT+sN2SOj4YmKPBtcwHI75uNZp7XRy85jRjrvhahg5baIQ0u3aL8aMA==',
    ),
    1 => 
    array (
      'encryption' => true,
      'signing' => false,
      'type' => 'X509Certificate',
      'X509Certificate' => 'MIIEkjCCA3qgAwIBAgIJAL90CxMEVb/kMA0GCSqGSIb3DQEBBQUAMIGMMQswCQYDVQQGEwJOTDELMAkGA1UECBMCTkgxEjAQBgNVBAcTCUFtc3RlcmRhbTEPMA0GA1UEChMGVEVSRU5BMQwwCgYDVQQLEwNJVFMxHjAcBgNVBAMTFWh0dHBzOi8vdGVyZW5hLm9yZy9zcDEdMBsGCSqGSIb3DQEJARYOYWFpQHRlcmVuYS5vcmcwHhcNMTEwMTEyMTUyNjM4WhcNMjEwMTExMTUyNjM4WjCBjDELMAkGA1UEBhMCTkwxCzAJBgNVBAgTAk5IMRIwEAYDVQQHEwlBbXN0ZXJkYW0xDzANBgNVBAoTBlRFUkVOQTEMMAoGA1UECxMDSVRTMR4wHAYDVQQDExVodHRwczovL3RlcmVuYS5vcmcvc3AxHTAbBgkqhkiG9w0BCQEWDmFhaUB0ZXJlbmEub3JnMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwTxx8JBWSpBJiZgdvGOJDXLwaE29Opx1CBbIrYHm47Oy4btsf0BzCmfdSPDlydDm6//355hsQU8BgIh/waEwFZZCg/XyzrJEXCDTZBm1H210aT7FNp356azqKOO1bYWcku0xpFOWWf3jCIkjtOiTkbl12Tw7Y+zJRhV2+jleC5td3JxZ6k1qotgN+1cGwZ2Tv2HhSNeMC4QsGOyBqeP+7B1CLFqFZSiLWGVqcZi0fGkXf+SrTSEH/kLzdciEg2EePyQPcLCKNz9RiIhSmsLE/Rr1ksOvZGmyWFe7YsPyJOLsNyYcZTufDVwpl9fDuJdYy2GdMT1kSNNOpZXZ7QcgYwIDAQABo4H0MIHxMB0GA1UdDgQWBBQ6tVqjpKC8+30XF/qWlaZ3fUKTvDCBwQYDVR0jBIG5MIG2gBQ6tVqjpKC8+30XF/qWlaZ3fUKTvKGBkqSBjzCBjDELMAkGA1UEBhMCTkwxCzAJBgNVBAgTAk5IMRIwEAYDVQQHEwlBbXN0ZXJkYW0xDzANBgNVBAoTBlRFUkVOQTEMMAoGA1UECxMDSVRTMR4wHAYDVQQDExVodHRwczovL3RlcmVuYS5vcmcvc3AxHTAbBgkqhkiG9w0BCQEWDmFhaUB0ZXJlbmEub3JnggkAv3QLEwRVv+QwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQUFAAOCAQEAn+06i7zZE7MjuB68gCaNvnCkrgfumi4PWiP6kaE6+LU2MTbxdFyoSAoKh6Ft9TDi+8ANAsn5jRQ5xLUE4YoVbub/KufMwdlX0zO9i+Q//npDTFESnWsiMi7DHg/av1LtzrYYZvE2E1e5c/7wo/axx8Bk7qsE9YXFRs372vDkDwOGSkLbRtgwdCUX47CE/fXvccPDHH217XMed2cVOGFjQgidsFZlJbSfSvQjWYw5LIE0wo9RtsEu5I3WAIar8Wr6/nhVOgIBUStpcw94GwlPxLywfij5CJ9HT+sN2SOj4YmKPBtcwHI75uNZp7XRy85jRjrvhahg5baIQ0u3aL8aMA==',
    ),
    2 => 
    array (
      'encryption' => false,
      'signing' => true,
      'type' => 'X509Certificate',
      'X509Certificate' => 'MIICGTCCAYICCQCTYS2pMIdaaDANBgkqhkiG9w0BAQUFADBRMQswCQYDVQQGEwJOTDELMAkGA1UECBMCTkgxEjAQBgNVBAcTCUFtc3RlcmRhbTEhMB8GA1UEChMYSW50ZXJuZXQgV2lkZ2l0cyBQdHkgTHRkMB4XDTEwMDkwMTE4NTMyMFoXDTM4MDExNjE4NTMyMFowUTELMAkGA1UEBhMCTkwxCzAJBgNVBAgTAk5IMRIwEAYDVQQHEwlBbXN0ZXJkYW0xITAfBgNVBAoTGEludGVybmV0IFdpZGdpdHMgUHR5IEx0ZDCBnzANBgkqhkiG9w0BAQEFAAOBjQAwgYkCgYEAvhzp9Tk7zQC+q5cpS36FMaaX3uQp6Sksnh+EnFtKFKeR4lKPmmgCSmVZkFdkgd7cE/vfFBInrQdb2rvLZrICdQbyLDuhQJUyqZHK52nbtP5FNcRN7V9LjpBB3CsUznenQoJLrpdPogSMDlSPjeLTaeB697EdZTt7IAmWDQmWWb8CAwEAATANBgkqhkiG9w0BAQUFAAOBgQAbKXFszQd2En/pqaxHWPISCqcpPfAxXXm7PNZ+sem6TYbtJuY7V68T1izuvax10FFgXBoltLKTg9IOdou6ZO+g5JrdMq1sOCQL/kILdmaIUZjm/hIeoygbwN3I0LhoxZbJqgWT5+gLtb+7JGRmbs8WE/3/Wm5i17ITMxptGjZQnQ==',
    ),
  ),
);

