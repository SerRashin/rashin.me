import DarkModeToggle from 'react-dark-mode-toggle';
import {AppContextConsumer} from '../AppContext';
import {DarkMode} from "use-dark-mode";

export interface ThemeTogglerInterface {
  onClick: () => void
}

const ThemeToggler = ({onClick}: ThemeTogglerInterface) => {
  const handleOnChange = (darkMode: DarkMode) => {
    darkMode.toggle();
    onClick();
  };

  return (
      <AppContextConsumer>
        {appContext => appContext && (
            <div style={{ marginBottom: 8 }}>
              <DarkModeToggle
                  onChange={() => handleOnChange(appContext.darkMode)}
                  checked={appContext.darkMode.value}
                  size={50}
              />
            </div>
        )}
      </AppContextConsumer>
  );
}

export default ThemeToggler;
