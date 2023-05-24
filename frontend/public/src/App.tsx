import './App.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import {BrowserRouter} from 'react-router-dom';
import { ThemeProvider } from 'styled-components';
import useDarkMode from 'use-dark-mode';
import {AppContextProvider} from './AppContext';
import MainApp from './MainApp';
import GlobalStyles from './theme/GlobalStyles';
import { lightTheme, darkTheme } from './theme/themes';
import NavBarWithRouter from "./components/NavBar";

function App() {
  const darkMode = useDarkMode();

  return (
      <AppContextProvider value={{ darkMode }}>
          <ThemeProvider theme={darkMode.value ? darkTheme : lightTheme}>
              <GlobalStyles />
              <BrowserRouter>
                  <NavBarWithRouter />
                  <MainApp />
              </BrowserRouter>
          </ThemeProvider>
      </AppContextProvider>
  );
}

export default App;
