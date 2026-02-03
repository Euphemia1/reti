import * as React from "react"

const MOBILE_BREAKPOINT = 768

export function useIsMobile() {
    const [isMobile, setIsMobile] = React.useState(undefined)

    React.useEffect(() => {
        const mql = window.matchMedia(`(max-width: ${MOBILE_BREAKPOINT - 1}px)`)
        const onChange = () => {
            setIsMobile(window.innerWidth < MOBILE_BREAKPOINT)
        }
        mql.addEventListener("change", onChange)
        setIsMobile(window.innerWidth < MOBILE_BREAKPOINT)
        return () => mql.removeEventListener("change", onChange);
    }, [])

    return !!isMobile
}

const appParams = {
    appId: import.meta.env.VITE_BASE44_APP_ID,
    token: import.meta.env.VITE_BASE44_API_KEY,
    functionsVersion: import.meta.env.VITE_BASE44_FUNCTIONS_VERSION || 'v1',
    appBaseUrl: import.meta.env.VITE_BASE44_APP_BASE_URL
};

export { appParams };
