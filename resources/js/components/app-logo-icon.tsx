import type { SVGAttributes } from 'react';

export default function AppLogoIcon(props: SVGAttributes<SVGElement>) {
    return (
        <img {...(props as any)} src="/favicon.png" alt="ScribeHub Logo" />
    );
}
