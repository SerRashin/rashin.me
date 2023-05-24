import { Link } from "react-router-dom";
import {CSSProperties} from "react";

const styles: {[key: string]: CSSProperties} = {
    mainContainer: {
        height: '100%',
        display: 'flex',
        flexDirection: 'column',
        justifyContent: 'center',
        alignItems: 'center',
    }
};

function NotFound() {
    return (
        <div style={styles}>
            <h2>404 - Not Found!</h2>
            <p>
                <Link to="/">Go to the home page</Link>
            </p>
        </div>
    );
}
export default NotFound;
