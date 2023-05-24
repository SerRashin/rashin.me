import {useContext, CSSProperties} from 'react';
import { SocialIcon } from 'react-social-icons';
import { ThemeContext } from 'styled-components';

const styles: {[key: string]: CSSProperties} = {
  iconStyle: {
    marginLeft: 10,
    marginRight: 10,
    marginBottom: 10,
  },
};

interface SocialParams {
  github: string,
  linkedin: string,
  email: string,
}

const Social = ({github, linkedin, email}: SocialParams) => {
  const theme = useContext(ThemeContext);
  const mailTo = 'mailto:' + email;

  return (
    <div className="social">

      {linkedin ? (
        <SocialIcon
          style={styles.iconStyle}
          url={linkedin}
          network='linkedin'
          bgColor={theme.socialIconBgColor}
          target="_blank"
          rel="noopener"
        />
      ) : null}

      {github ? (
        <SocialIcon
          style={styles.iconStyle}
          url={github}
          network='github'
          bgColor={theme.socialIconBgColor}
          target="_blank"
          rel="noopener"
        />
      ) : null}

      {github ? (
        <SocialIcon
          style={styles.iconStyle}
          url={mailTo}
          network='email'
          bgColor={theme.socialIconBgColor}
          target="_blank"
          rel="noopener"
        />
      ) : null}
    </div>
  );
}

export default Social;
