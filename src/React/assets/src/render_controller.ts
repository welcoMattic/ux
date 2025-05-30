/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from '@hotwired/stimulus';
import React, { type ReactElement } from 'react';
import { createRoot } from 'react-dom/client';

export default class extends Controller {
    declare readonly componentValue?: string;
    declare readonly propsValue?: object;
    declare readonly permanentValue: boolean;

    static values = {
        component: String,
        props: Object,
        permanent: { type: Boolean, default: false },
    };

    connect() {
        const props = this.propsValue ? this.propsValue : null;
        this.dispatchEvent('connect', { component: this.componentValue, props: props });
        if (!this.componentValue) {
            throw new Error('No component specified.');
        }

        const component = window.resolveReactComponent(this.componentValue);
        this._renderReactElement(React.createElement(component, props, null));

        this.dispatchEvent('mount', {
            componentName: this.componentValue,
            component: component,
            props: props,
        });
    }

    disconnect() {
        if (this.permanentValue) {
            // Prevent unmounting the component if the controller is permanent
            // (no render is allowed after unmounting)
            return;
        }

        (this.element as any).root.unmount();
        this.dispatchEvent('unmount', {
            component: this.componentValue,
            props: this.propsValue ? this.propsValue : null,
        });
    }

    _renderReactElement(reactElement: ReactElement) {
        const element: any = this.element as any;

        // If a root has already been created for this element, reuse it
        if (!element.root) {
            element.root = createRoot(this.element);
        }

        element.root.render(reactElement);
    }

    private dispatchEvent(name: string, payload: any) {
        this.dispatch(name, { detail: payload, prefix: 'react' });
    }
}
