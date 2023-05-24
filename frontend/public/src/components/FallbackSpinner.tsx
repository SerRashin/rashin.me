import { Spinner } from 'react-bootstrap';
import {CSSProperties} from "react";

const styles: {[key: string]: CSSProperties} = {
  spinnerContainerStyle: {
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    height: '100%',
  },
};

const FallbackSpinner = () => {
  return (
    <div style={styles.spinnerContainerStyle}>
      <Spinner animation="grow" />
    </div>
  );
}

export default FallbackSpinner;
