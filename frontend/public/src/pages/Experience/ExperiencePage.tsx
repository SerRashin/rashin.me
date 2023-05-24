import {useEffect, useState, useContext, CSSProperties} from 'react';
// @ts-ignore
import { Timeline, TimelineItem } from 'vertical-timeline-component-for-react';
import { Container } from 'react-bootstrap';
import ReactMarkdown from 'react-markdown';
import { ThemeContext } from 'styled-components';
import { Fade } from 'react-awesome-reveal';
import '../../css/experience.css';
import ApiService, {Job, PaginatedCollection} from "../../services/ApiService";
import Header from "../../components/Header";
import FallbackSpinner from "../../components/FallbackSpinner";

const styles: {[key: string]: CSSProperties} = {
  ulStyle: {
    listStylePosition: 'outside',
    paddingLeft: 20,
  },
  subtitleContainerStyle: {
    marginTop: 10,
    marginBottom: 10,
  },
  subtitleStyle: {
    display: 'inline-block',
  },
  inlineChild: {
    display: 'inline-block',
  },
  itemStyle: {
    marginBottom: 10,
  },
};

interface ExperienceListProps {
  header: string
}

const ExperiencePage = ({ header }: ExperienceListProps) => {
  const theme = useContext(ThemeContext);

  const [data, setData] = useState<PaginatedCollection<Job> | null>(null);

  useEffect(() => {
    ApiService.getJobs()
      .then((response: PaginatedCollection<Job>) => {
        setData(response)
      });
  }, []);

  return (
    <>
      <Header title={header} />

      {data
        ? (
          <div className="section-content-container">
            <Container>
              <Fade cascade>
              <Timeline
                lineColor={theme.timelineLineColor}
              >
                {data.data.map((item: Job) => (
                    <TimelineItem
                      key={item.id}
                      dateText={`${item.from.month}/${item.from.year} - ${item.to ? item.to.month + '/' + item.to.year : 'Until Now'}`}
                      dateInnerStyle={{ background: theme.accentColor }}
                      style={styles.itemStyle}
                      bodyContainerStyle={{ color: theme.color }}
                    >
                      <h2 className="item-title">
                        {item.name}
                      </h2>
                      <div style={styles.subtitleContainerStyle}>
                        <h4 style={{ ...styles.subtitleStyle, color: theme.accentColor }}>
                          <a href={item.company.url} target='_blank'>{item.company.name}</a>
                        </h4>
                        {item.type && (
                          <h5 style={styles.inlineChild}>
                            &nbsp;·
                            {' '}
                            {item.type}
                          </h5>
                        )}
                      </div>
                      <ul style={styles.ulStyle}>
                        <div>
                          <ReactMarkdown
                            children={item.description}
                            components={{
                              p: 'span',
                            }}
                          />
                          <br />
                        </div>
                      </ul>
                    </TimelineItem>
                ))}
              </Timeline>
              </Fade>
            </Container>
          </div>
        ) : <FallbackSpinner /> }
    </>
  );
}

export default ExperiencePage;
