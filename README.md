# FluxOpenIdConnectApi

## Environment variables

| Variable | Description | Default value |
| -------- | ----------- | ------------- |
| FLUX_OPEN_ID_CONNECT_API_SERVER_HTTPS_CERT | Path to HTTPS certificate file<br>Set this will enable listen on HTTPS<br>Should be on a volume | - |
| FLUX_OPEN_ID_CONNECT_API_SERVER_HTTPS_KEY | Path to HTTPS key file<br>Should be on a volume | - |
| FLUX_OPEN_ID_CONNECT_API_SERVER_LISTEN | Listen IP | 0.0.0.0 |
| FLUX_OPEN_ID_CONNECT_API_SERVER_PORT | Listen port | 9501 |
| **FLUX_OPEN_ID_CONNECT_API_PROVIDER_URl** | OpenIdConnect server url | - |
| **FLUX_OPEN_ID_CONNECT_API_PROVIDER_CLIENT_ID** | OpenIdConnect client id | - |
| **FLUX_OPEN_ID_CONNECT_API_PROVIDER_CLIENT_SECRET** | OpenIdConnect client secret | - |
| **FLUX_OPEN_ID_CONNECT_API_PROVIDER_REDIRECT_URI** | OpenIdConnect redirect uri<br>Like `https://%host%/callback` | - |
| FLUX_OPEN_ID_CONNECT_API_PROVIDER_SCOPE | OpenIdConnect server scopes | openid profile email |
| FLUX_OPEN_ID_CONNECT_API_PROVIDER_SUPPORTS_PKCE | Whether OpenIdConnect server supports proof key for code exchange<br>Recommended to use this for additional security | true |
| FLUX_OPEN_ID_CONNECT_API_PROVIDER_TRUST_SELF_SIGNED_CERTIFICATE | If you use a self signed certificate, you need to trust it manually | false |
| **FLUX_OPEN_ID_CONNECT_API_SESSION_CRYPT_SECRET** | Secret for encrypt the cookie<br>Should be a generated random value | - |
| FLUX_OPEN_ID_CONNECT_API_SESSION_CRYPT_METHOD | Algorithm method | aes-256-cbc |
| FLUX_OPEN_ID_CONNECT_API_SESSION_CRYPT_PLAIN | Bypass encrypt cookie for dev environment | false |
| FLUX_OPEN_ID_CONNECT_API_ROUTE_AFTER_LOGIN_URL | Url to redirect after login | / |
| FLUX_OPEN_ID_CONNECT_API_ROUTE_AFTER_LOGOUT_URL | Url to redirect after logout | / |
| FLUX_OPEN_ID_CONNECT_API_COOKIE_NAME | Cookie name | auth |
| FLUX_OPEN_ID_CONNECT_API_COOKIE_EXPIRES | Cookie expires as timestamp | (Session end) |
| FLUX_OPEN_ID_CONNECT_API_COOKIE_PATH | Cookie path | / |
| FLUX_OPEN_ID_CONNECT_API_COOKIE_DOMAIN | Cookie domain | - |
| FLUX_OPEN_ID_CONNECT_API_COOKIE_SECURE | Cookie secure | true |
| FLUX_OPEN_ID_CONNECT_API_COOKIE_HTTP_ONLY | Cookie http only | true |
| FLUX_OPEN_ID_CONNECT_API_COOKIE_SAME_SITE | Cookie same site<br>Lax, Strict or None | Lax |
| FLUX_OPEN_ID_CONNECT_API_COOKIE_PRIORITY | Cookie priority<br>Low, Medium or High | Medium |

Minimal variables required to set are **bold**

## Examples

[examples](examples)
