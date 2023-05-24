import * as React from 'react';
import {DarkMode} from "use-dark-mode";

export interface AppContextInterface {
    darkMode: DarkMode
}

const {Provider, Consumer} = React.createContext<AppContextInterface | null>(null);

export const AppContextProvider = Provider;

export const AppContextConsumer = Consumer;
